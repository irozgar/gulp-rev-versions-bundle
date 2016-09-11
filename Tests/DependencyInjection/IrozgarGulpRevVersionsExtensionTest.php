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
    public function testAddsParameterWithManifestPathToContainer()
    {
        $configuration = new ContainerBuilder();
        $loader = new IrozgarGulpRevVersionsExtension();
        $config = $this->getEmptyConfig();
        $loader->load(array($config), $configuration);

        $this->assertEquals(Configuration::DEFAULT_MANIFEST_PATH, $configuration->getParameter('gulp_rev_manifest_path'));
    }

    public function getEmptyConfig()
    {
        $yaml = <<<EOF
manifest_path: ~
EOF;

        $parser = new Parser();
        return $parser->parse($yaml);

    }
}
