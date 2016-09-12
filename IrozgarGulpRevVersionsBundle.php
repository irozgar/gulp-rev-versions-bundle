<?php

namespace Irozgar\GulpRevVersionsBundle;

use Irozgar\GulpRevVersionsBundle\DependencyInjection\Compiler\SetVersionStrategyCompiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

class IrozgarGulpRevVersionsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // Register only for versions lower than Symfony 3.0 because the don't have the option for
        // setting the version_strategy

        $this->addCompilerPassWhenVersionIsLowerThan($container, new SetVersionStrategyCompiler(), 30000);
    }

    public function addCompilerPassWhenVersionIsLowerThan(
        ContainerBuilder $container,
        CompilerPassInterface $compilerPass,
        $maxVersion,
        $currentVersion = Kernel::VERSION_ID
    ) {
        if ($currentVersion < $maxVersion) {
            $container->addCompilerPass($compilerPass);
        }
    }
}
