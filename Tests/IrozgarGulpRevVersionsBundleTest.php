<?php
/*
 * This file is part of the GulpRevVersionStrategy.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Irozgar\GulpRevVersionsBundle\Tests;

use Irozgar\GulpRevVersionsBundle\IrozgarGulpRevVersionsBundle;
use PHPUnit_Framework_Error_Deprecated;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Throwable;

class IrozgarGulpRevVersionsBundleTest extends PHPUnit_Framework_TestCase
{
    public function testCompilerPassIsAddedIfSymfonyVersionIsLessThan30100()
    {
        if (Kernel::VERSION_ID < 30100) {
            $container = new ContainerBuilder();
            $bundle = new IrozgarGulpRevVersionsBundle();
            $bundle->build($container);

            $this->assertArrayHasInstanceOf(
                'Irozgar\GulpRevVersionsBundle\DependencyInjection\Compiler\SetVersionStrategyCompiler',
                $container->getCompilerPassConfig()->getPasses()
            );
        }
    }

    public function testCompilerPassIsNotAddedIfSymfonyVersionIsGreaterThanOrEquals30100()
    {
        if (Kernel::VERSION_ID >= 30100) {
            $container = new ContainerBuilder();
            $bundle = new IrozgarGulpRevVersionsBundle();
            $bundle->build($container);

            $this->assertArrayHasNoInstanceOf(
                'Irozgar\GulpRevVersionsBundle\DependencyInjection\Compiler\SetVersionStrategyCompiler',
                $container->getCompilerPassConfig()->getPasses()
            );
        }
    }

    public function testTriggersDeprecationErrorForSymfonyVersionsLowerThan30300()
    {
        if (Kernel::VERSION_ID >= 30300) {
            $this->markTestSkipped('Symfony version is greater or equal to 3.3');
        }

        $originalErrorReporting = ini_get('error_reporting');
        // Report deprecations messages during this test
        ini_set('error_reporting', '-1');

        $deprecationMessage = '';
        try {
            new IrozgarGulpRevVersionsBundle();
        } catch (Throwable $e) {
            if (!$e instanceof PHPUnit_Framework_Error_Deprecated) {
                throw $e;

            }
            $deprecationMessage = $e->getMessage();
        } finally {
            // Restore error_reporting
            ini_set('error_reporting', $originalErrorReporting);
        }

        $expectedDeprecationMessage = 'The bundle IrozgarGulpRevVersionsBundle is deprecated and will be abandoned '.
            'when symfony 2.8 support finishes on November 2019. I recommend updating your symfony version to the '.
            'last stable version and use the option "json_manifest_path" included in symfony since version 3.3.';
        self::assertSame($expectedDeprecationMessage, $deprecationMessage);
    }

    public function testTriggersDeprecationErrorForSymfonyVersionsGreaterOrEqualTo30300()
    {
        if (Kernel::VERSION_ID < 30300) {
            $this->markTestSkipped('Symfony version is lower than 3.3');
        }

        $originalErrorReporting = ini_get('error_reporting');
        // Report deprecations messages during this test
        ini_set('error_reporting', '-1');

        $deprecationMessage = '';
        try {
            new IrozgarGulpRevVersionsBundle();
        } catch (Throwable $e) {
            if (!$e instanceof PHPUnit_Framework_Error_Deprecated) {
                throw $e;
            }

            $deprecationMessage = $e->getMessage();
        } finally {
            // Restore error_reporting
            ini_set('error_reporting', $originalErrorReporting);
        }


        $expectedDeprecationMessage = 'The bundle IrozgarGulpRevVersionsBundle is deprecated and will be abandoned '.
            'when symfony 2.8 support finishes on November 2019. Since version 3.3, symfony includes the option '.
            '"json_manifest_path" that does the same as this bundle, I recommend using that instead of this bundle.';
        self::assertSame($expectedDeprecationMessage, $deprecationMessage);
    }

    protected function assertArrayHasInstanceOf($class, $haystack)
    {
        $found = false;
        foreach ($haystack as $item) {
            if ($item instanceof $class) {
                $found = true;
            }
        }

        $this->assertTrue($found);
    }

    protected function assertArrayHasNoInstanceOf($class, $haystack)
    {
        $found = false;
        foreach ($haystack as $item) {
            if ($item instanceof $class) {
                $found = true;
            }
        }

        $this->assertFalse($found);
    }
}
