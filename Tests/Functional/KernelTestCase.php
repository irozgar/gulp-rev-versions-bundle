<?php

namespace Irozgar\GulpRevVersionsBundle\Tests\Functional;

use Irozgar\GulpRevVersionsBundle\Tests\Functional\app\AppKernel;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ResettableContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class KernelTestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @var KernelInterface
     */
    protected static $kernel;

    /**
     * Boots the Kernel for this test.
     *
     * @param array $options
     */
    protected static function bootKernel(array $options = array())
    {
        static::ensureKernelShutdown();

        static::$kernel = static::createKernel($options);
        static::$kernel->boot();
    }

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     *
     * @param array $options An array of options
     *
     * @return KernelInterface A KernelInterface instance
     */
    protected static function createKernel(array $options = array())
    {
        return new AppKernel(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }

    /**
     * Shuts the kernel down if it was used in the test.
     */
    protected static function ensureKernelShutdown()
    {
        if (null !== static::$kernel) {
            $container = static::$kernel->getContainer();
            static::$kernel->shutdown();
            if ($container instanceof ResettableContainerInterface) {
                $container->reset();
            }
        }
    }

    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
        static::ensureKernelShutdown();
    }
}
