<?php
/*
 * This file is part of the GulpRevVersionStrategy.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Irozgar\GulpRevVersionsBundle\Tests\Asset;

use Irozgar\GulpRevVersionsBundle\Asset\GulpRevVersionStrategy;
use PHPUnit_Framework_TestCase;

class GulpRevVersionStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testGetVersion()
    {
        $strategy = new GulpRevVersionStrategy(__DIR__.'/../', 'Fixtures/rev-manifest.json');

        $this->assertEquals('07fb3d8168', $strategy->getVersion('js/scripts.js'));
    }

    public function testGetApplyVersion()
    {
        $strategy = new GulpRevVersionStrategy(__DIR__.'/../', 'Fixtures/rev-manifest.json');

        $this->assertEquals('js/scripts-07fb3d8168.js', $strategy->applyVersion('js/scripts.js'));
    }

    public function testPathsAreLoadedAfterFirstUse()
    {
        $strategy = new GulpRevVersionStrategy(__DIR__.'/../', 'Fixtures/rev-manifest.json');

        $strategy->applyVersion('js/scripts.js');

        $this->assertAttributeNotEmpty('paths', $strategy);
    }
}
