<?php

namespace Irozgar\GulpRevVersionsBundle;

use Irozgar\GulpRevVersionsBundle\DependencyInjection\Compiler\SetVersionStrategyCompiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

class IrozgarGulpRevVersionsBundle extends Bundle
{
    public function __construct()
    {
        $deprecationMessage = 'The bundle IrozgarGulpRevVersionsBundle is deprecated and will be abandoned when '.
            'symfony 2.8 support finishes on November 2019. ';
        if (Kernel::VERSION_ID >= 30300) {
            $deprecationMessage .= 'Since version 3.3, symfony includes the option "json_manifest_path" that does the '.
                'same as this bundle, I recommend using that instead of this bundle.';
        }
        if (Kernel::VERSION_ID < 30300) {
            $deprecationMessage .= 'I recommend updating your symfony version to the last stable version and use '.
                'the option "json_manifest_path" included in symfony since version 3.3.';
        }
        trigger_error($deprecationMessage, E_USER_DEPRECATED);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // Register only for versions lower than Symfony 3.1 because the don't have the option for
        // setting the version_strategy
        $this->addCompilerPassWhenVersionIsLowerThan($container, new SetVersionStrategyCompiler(), 30100);
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
