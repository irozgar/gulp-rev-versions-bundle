<?php

/*
 * This file is part of the GulpRevVersionStrategy.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Irozgar\GulpRevVersionsBundle\Tests\Functional\app;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir(). '/'. Kernel::VERSION.'/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return sys_get_temp_dir(). '/'. Kernel::VERSION.'/logs/';
    }

    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Irozgar\GulpRevVersionsBundle\Tests\Functional\TestBundle\TestBundle(),
            new \Irozgar\GulpRevVersionsBundle\IrozgarGulpRevVersionsBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        if (Kernel::VERSION_ID < 30100) {
            $loader->load(__DIR__ .'/config/config_previous_3-1.yml');
        } else {
            $loader->load(__DIR__ .'/config/config.yml');
        }
    }
}
