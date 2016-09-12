<?php
/*
 * This file is part of the GulpRevVersionStrategy.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Irozgar\GulpRevVersionsBundle\Tests\DependencyInjection;

use Irozgar\GulpRevVersionsBundle\DependencyInjection\Configuration;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testEmptyManifestPathUsesDefaultPath()
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, array());

        $this->assertArrayHasKey('manifest_path', $config);
        $this->assertContains('Resources/assets/rev-manifest.json', $config);
    }

    public function testNullManifestPathUsesDefaultPath()
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, array($this->getEmptyConfig()));

        $this->assertArrayHasKey('manifest_path', $config);
        $this->assertContains('Resources/assets/rev-manifest.json', $config);
    }

    public function testEmptyReplaceDefaultVersionStrategy()
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, array());

        $this->assertArrayHasKey('replace_default_version_strategy', $config);
        $this->assertFalse($config['replace_default_version_strategy']);
    }

    public function testNullReplaceDefaultVersionStrategy()
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, array($this->getEmptyConfig()));

        $this->assertArrayHasKey('replace_default_version_strategy', $config);
        $this->assertTrue($config['replace_default_version_strategy']);
    }

    public function getEmptyConfig()
    {
        $yaml = <<<'EOF'
manifest_path: ~
replace_default_version_strategy: ~
EOF;

        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
