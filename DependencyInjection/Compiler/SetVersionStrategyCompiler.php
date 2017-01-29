<?php
/*
 * This file is part of the GulpRevVersionStrategy.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Irozgar\GulpRevVersionsBundle\DependencyInjection\Compiler;

use Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class SetVersionStrategy.
 *
 * @author Isaac Rozas García <irozgar@gmail.com>
 */
class SetVersionStrategyCompiler implements CompilerPassInterface
{
    private $map = array(
        'Symfony\\Component\\Asset\\Package' => 0,
        'Symfony\\Component\\Asset\\PathPackage' => 1,
        'Symfony\\Component\\Asset\\UrlPackage' => 1,
    );

    public function process(ContainerBuilder $container)
    {
        if (!$container->has('irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy')) {
            return;
        }

        // Replace the default strategy
        if ($container->getParameter('gulp_rev_replace_strategy')) {
            $this->replaceDefaultStrategy($container);
        }

        $this->replaceStrategy($container);
    }

    private function getIndexForVersionStrategyArgument($class)
    {
        if (!isset($this->map[$class])) {
            throw new Exception(sprintf('Packages of type "%s" are not supported by IrozgarGulpRevBundle', $class));
        }

        return $this->map[$class];
    }

    private function getPackageClass(Definition $package, ContainerBuilder $container)
    {
        if ($package->getClass() !== null) {
            return $package->getClass();
        }

        if ($package instanceof DefinitionDecorator) {
            $parent = $container->getDefinition($package->getParent());
            $class = $this->getPackageClass($parent, $container);
            if ($class === null) {
                throw new Exception(
                    sprintf('Unable to resolve the class of the service "%s"', $package->getParent())
                );
            }

            return $class;
        }

        return null;
    }

    /**
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    private function replaceDefaultStrategy(ContainerBuilder $container)
    {
        if (!$container->has('assets._default_package')) {
            throw new Exception('Default package does not exist.');
        }

        $defaultPackage = $container->getDefinition('assets._default_package');

        $class = $this->getPackageClass($defaultPackage, $container);

        if ($class === null) {
            throw new Exception(
                sprintf('Unable to resolve the class of the service "%s"', 'assets._default_package')
            );
        }

        $index = $this->getIndexForVersionStrategyArgument($class);

        $defaultPackage->replaceArgument(
            $index,
            new Reference('irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy')
        );
    }

    private function replaceStrategy(ContainerBuilder $container)
    {
        $packages = $container->getDefinition('assets.packages')->getArgument(1);
        $packagesToReplace = $this->createNamedPackageIdsArray($container->getParameter('irozgar_gulp_rev.packages'));

        foreach ($packages as $package) {
            if (!in_array($package, $packagesToReplace)) {
                continue;
            }

            $definition = $container->getDefinition((string) $package);
            $definition->replaceArgument(1, new Reference('irozgar_gulp_rev_versions.asset.gulp_rev_version_strategy'));
        }
    }

    private function createNamedPackageIdsArray(array $names)
    {
        return array_map(function ($name) {
            return 'assets._package_'.$name;
        }, $names);
    }
}
