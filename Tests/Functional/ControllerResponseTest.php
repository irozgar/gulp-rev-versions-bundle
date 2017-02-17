<?php

namespace Irozgar\GulpRevVersionsBundle\Tests\Functional;

use Irozgar\GulpRevVersionsBundle\Tests\Functional\app\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllerResponseTest extends WebTestCase
{
    public function testVersionIsPresentAtTheEndOfTheAssetUrl()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("/styles-a41d8cd1.css", $crawler->filterXPath('//link/@href')->text());
    }

    public function testIgnoresFiles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("/script.js", $crawler->filterXPath('//script/@src')->text());
    }

    protected static function getKernelClass()
    {
        return AppKernel::class;
    }
}
