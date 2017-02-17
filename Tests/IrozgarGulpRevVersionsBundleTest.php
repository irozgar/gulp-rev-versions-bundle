<?php
/*
 * This file is part of the GulpRevVersionStrategy.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Irozgar\GulpRevVersionsBundle\Tests;

use Irozgar\GulpRevVersionsBundle\IrozgarGulpRevVersionsBundle;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

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
