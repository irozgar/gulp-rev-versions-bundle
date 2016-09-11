<?php
/**
 * This file is part of GulpRevVersionStrategy
 *
 * For the full copyright and license information, please view de LICENSE
 * file that is in the root of this project
 */
namespace Irozgar\GulpRevVersionsBundle\Tests\DependencyInjection\Compiler;

use Irozgar\GulpRevVersionsBundle\DependencyInjection\Compiler\SetVersionStrategyCompiler;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class SetVersionStrategyCompilerTest extends PHPUnit_Framework_TestCase
{
    public function testAddsTheVersionStrategy()
    {
        $container = new ContainerBuilder();

        $strategy = $this->getMockBuilder('Irozgar\GulpRevVersionsBundle\Asset\GulpRevVersionStrategy')
            ->disableOriginalConstructor()
            ->getMock();
        $package = $this->createPackageDefinition($container);


        $container->setParameter('gulp_rev_replace_strategy', true);
        $container->register('irozgar_gulp_dev_versions.asset.gulp_rev_version_strategy', $strategy);


        $compiler = new SetVersionStrategyCompiler();
        $compiler->process($container);

        $this->assertEquals(
            'irozgar_gulp_dev_versions.asset.gulp_rev_version_strategy',
            (string)$package->getArgument(1)
        );
    }

    public function testDoNotAddTheVersionStrategyWhenOptionIsNotEnabled()
    {
        $container = new ContainerBuilder();

        $strategy = $this->getMockBuilder('Irozgar\GulpRevVersionsBundle\Asset\GulpRevVersionStrategy')
            ->disableOriginalConstructor()
            ->getMock();
        $package = $this->createPackageDefinition($container);


        $container->setParameter('gulp_rev_replace_strategy', false);
        $container->register('irozgar_gulp_dev_versions.asset.gulp_rev_version_strategy', $strategy);


        $compiler = new SetVersionStrategyCompiler();
        $compiler->process($container);

        $this->assertEquals(
            (string)$this->createVersion($container),
            (string)$package->getArgument(1)
        );
    }

    private function createPackageDefinition(ContainerBuilder $container)
    {
        $package = new Definition('Symfony\Component\Asset\PathPackage');
        $package->setAbstract(true);
        $decoratedPackage = new DefinitionDecorator('assets.path_package');
        $decoratedPackage->setArguments(array('/', $this->createVersion($container)));

        $container->setDefinition('assets.path_package', $package);
        $container->setDefinition('assets._default_package', $decoratedPackage);

        return $decoratedPackage;
    }

    private function createVersion(ContainerBuilder $container)
    {
        $version = $this->getMockBuilder('Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy')
            ->disableOriginalConstructor()
            ->getMock();
        $container->register('assets.empty_version_strategy', $version);

        return new Reference('assets.empty_version_strategy');
    }
}
