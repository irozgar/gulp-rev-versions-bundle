<?php
/**
 * This file is part of GulpRevVersionStrategy.
 *
 * For the full copyright and license information, please view de LICENSE
 * file that is in the root of this project
 */

namespace Irozgar\GulpRevVersionsBundle\Tests\DependencyInjection\Compiler;

use Irozgar\GulpRevVersionsBundle\DependencyInjection\Compiler\SetVersionStrategyCompiler;
use PHPUnit_Framework_TestCase;
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
        $package = $this->createDefaultPackageDefinition($container);
        $this->createPackagesDefinition($container, array());

        $container->setParameter('gulp_rev_replace_strategy', true);
        $container->setParameter('irozgar_gulp_rev.packages', array('mycdn'));
        $container->register('irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy', $strategy);

        $compiler = new SetVersionStrategyCompiler();
        $compiler->process($container);

        $this->assertEquals(
            'irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy',
            (string) $package->getArgument(1)
        );
    }

    public function testDoNotAddTheVersionStrategyWhenOptionIsNotEnabled()
    {
        $container = new ContainerBuilder();

        $strategy = $this->getMockBuilder('Irozgar\GulpRevVersionsBundle\Asset\GulpRevVersionStrategy')
            ->disableOriginalConstructor()
            ->getMock();
        $package = $this->createDefaultPackageDefinition($container);
        $this->createPackagesDefinition($container, array());

        $container->setParameter('gulp_rev_replace_strategy', false);
        $container->setParameter('irozgar_gulp_rev.packages', array('mycdn'));
        $container->register('irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy', $strategy);

        $compiler = new SetVersionStrategyCompiler();
        $compiler->process($container);

        $this->assertEquals(
            (string) $this->createVersion($container),
            (string) $package->getArgument(1)
        );
    }

    public function testReplacesNamedPackages()
    {
        $container = new ContainerBuilder();

        $strategy = $this->getMockBuilder('Irozgar\GulpRevVersionsBundle\Asset\GulpRevVersionStrategy')
            ->disableOriginalConstructor()
            ->getMock();

        // Create packages name
        $packages = array();
        $namedPackageDefinition = array();
        foreach (array('mycdn', 'another') as $name) {
            /* @var DefinitionDecorator[] $namedPackageDefinition */
            $namedPackageDefinition[$name] = $this->createNamedPackageDefinition($name, $container);
            $packages[$name] = new Reference($this->getNamedPackageId($name));
        }
        $this->createDefaultPackageDefinition($container);
        $this->createPackagesDefinition($container, $packages);

        $container->setParameter('gulp_rev_replace_strategy', true);
        $container->setParameter('irozgar_gulp_rev.packages', array('mycdn', 'another'));
        $container->register('irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy', $strategy);

        $compiler = new SetVersionStrategyCompiler();
        $compiler->process($container);

        $this->assertEquals(
            'irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy',
            (string) $namedPackageDefinition['mycdn']->getArgument(1)
        );
        $this->assertEquals(
            'irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy',
            (string) $namedPackageDefinition['another']->getArgument(1)
        );
    }

    public function testReplacesOnlyConfiguredNamedPackages()
    {
        $container = new ContainerBuilder();

        $strategy = $this->getMockBuilder('Irozgar\GulpRevVersionsBundle\Asset\GulpRevVersionStrategy')
            ->disableOriginalConstructor()
            ->getMock();

        // Create packages name
        $packages = array();
        $namedPackageDefinition = array();
        foreach (array('mycdn', 'another') as $name) {
            /* @var DefinitionDecorator[] $namedPackageDefinition */
            $namedPackageDefinition[$name] = $this->createNamedPackageDefinition($name, $container);
            $packages[$name] = new Reference($this->getNamedPackageId($name));
        }
        $defaultPackageDefinition = $this->createDefaultPackageDefinition($container);
        $this->createPackagesDefinition($container, $packages);

        $container->setParameter('gulp_rev_replace_strategy', false);
        $container->setParameter('irozgar_gulp_rev.packages', array('mycdn'));
        $container->register('irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy', $strategy);

        $compiler = new SetVersionStrategyCompiler();
        $compiler->process($container);

        $this->assertEquals(
            'assets.empty_version_strategy',
            (string) $defaultPackageDefinition->getArgument(1)
        );
        $this->assertEquals(
            'irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy',
            (string) $namedPackageDefinition['mycdn']->getArgument(1)
        );
        $this->assertEquals(
            'assets.empty_version_strategy',
            (string) $namedPackageDefinition['another']->getArgument(1)
        );
    }

    private function createDefaultPackageDefinition(ContainerBuilder $container)
    {
        $package = new Definition('Symfony\Component\Asset\PathPackage');
        $package->setAbstract(true);
        $decoratedPackage = new DefinitionDecorator('assets.path_package');
        $decoratedPackage->setArguments(array('/', $this->createVersion($container)));

        $container->setDefinition('assets.path_package', $package);
        $container->setDefinition('assets._default_package', $decoratedPackage);

        return $decoratedPackage;
    }

    private function createNamedPackageDefinition($name, ContainerBuilder $container)
    {
        $package = new Definition('Symfony\Component\Asset\PathPackage');
        $package->setAbstract(true);
        $decoratedPackage = new DefinitionDecorator('assets.path_package');
        $decoratedPackage->setArguments(array('/', $this->createVersion($container)));

        $container->setDefinition($this->getNamedPackageId($name), $decoratedPackage);

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

    /**
     * @param $name
     *
     * @return string
     */
    private function getNamedPackageId($name)
    {
        return 'assets._package_'.$name;
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $packages
     */
    private function createPackagesDefinition(ContainerBuilder $container, array $packages)
    {
        $container->setDefinition('assets.packages', new Definition('Symfony\Component\Asset\Packages', array(
            new Reference('assets._default_package'),
            $packages,
        )));
    }
}
