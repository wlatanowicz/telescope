<?php

namespace wlatanowicz\AppBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use wlatanowicz\AppBundle\DependencyInjection\CompilerPass\LoggingMetadataCompilerPass;

class wlatanowiczAppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new LoggingMetadataCompilerPass());
    }
}
