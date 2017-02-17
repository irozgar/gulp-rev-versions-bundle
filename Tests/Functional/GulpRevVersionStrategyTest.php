<?php

namespace Irozgar\GulpRevVersionsBundle\Tests\Functional;

use Irozgar\GulpRevVersionsBundle\Asset\GulpRevVersionStrategy;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class GulpRevVersionStrategyTest extends KernelTestCase
{
    public function testVersionStrategyForDefaultPackageIsCorrectlySet()
    {
        static::bootKernel();

        /** @var Packages $service */
        $service = static::$kernel->getContainer()->get('assets.packages');

        $this->assertAttributeInstanceOf(GulpRevVersionStrategy::class, 'versionStrategy', $service->getPackage());
    }

    public function testVersionStrategyForNamedPackageIsCorrectlySet()
    {
        static::bootKernel();

        /** @var Packages $service */
        $service = static::$kernel->getContainer()->get('assets.packages');

        $this->assertAttributeInstanceOf(GulpRevVersionStrategy::class, 'versionStrategy', $service->getPackage('main'));
        $this->assertAttributeInstanceOf(EmptyVersionStrategy::class, 'versionStrategy', $service->getPackage('unversioned'));
    }
}
