<?php
/*
 * This file is part of the GulpRevVersionStrategy.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Irozgar\GulpRevVersionsBundle\Tests\DependencyInjection;

use Irozgar\GulpRevVersionsBundle\DependencyInjection\Configuration;
use Irozgar\GulpRevVersionsBundle\DependencyInjection\IrozgarGulpRevVersionsExtension;
use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class IrozgarGulpRevVersionsExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $configuration;

    protected function setUp()
    {
        $this->configuration = new ContainerBuilder();
    }

    public function testAddsParameterWithManifestPathToContainer()
    {
        $loader = new IrozgarGulpRevVersionsExtension();
        $config = $this->getEmptyConfig();
        $loader->load(array($config), $this->configuration);

        $this->assertEquals(Configuration::DEFAULT_MANIFEST_PATH, $this->configuration->getParameter('gulp_rev_manifest_path'));
    }

    public function testAddsPackagesArrayToContainer()
    {
        $loader = new IrozgarGulpRevVersionsExtension();
        $config = $this->getConfigWithPackages();
        $loader->load(array($config), $this->configuration);

        $this->assertEquals(array('mycdn', 'another'), $this->configuration->getParameter('irozgar_gulp_rev.packages'));
    }

    public function getEmptyConfig()
    {
        $yaml = <<<'EOF'
manifest_path: ~
EOF;

        $parser = new Parser();

        return $parser->parse($yaml);
    }

    public function getConfigWithPackages()
    {
        $yaml = <<<'EOF'
manifest_path: ~
packages:
    - mycdn
    - another
EOF;

        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
