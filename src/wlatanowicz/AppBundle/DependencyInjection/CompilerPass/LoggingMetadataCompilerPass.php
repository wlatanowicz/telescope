<?php

namespace wlatanowicz\AppBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use wlatanowicz\AppBundle\Helper\LoggerWithContext;

class LoggingMetadataCompilerPass implements CompilerPassInterface
{
    const METADATA_TAG_NAME = 'logger.metadata';

    private $loggers;

    /**
     * LoggingMetadataCompilerPass constructor.
     */
    public function __construct()
    {
        $this->loggers = [];
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds(self::METADATA_TAG_NAME);

        foreach ($services as $serviceId => $tags) {
            $context = [
                "color" => isset($tags[0]["color"]) ? $tags[0]["color"] : null,
                "display" => isset($tags[0]["display"]) ? $tags[0]["display"] : null,
            ];
            $definition = $container->getDefinition($serviceId);

            $loggerId = $this->getLoggerWithContext($context, $container);

            foreach ($definition->getArguments() as $index => $argument) {
                if ($argument instanceof Reference && 'logger' === (string) $argument) {
                    $definition->replaceArgument($index, $this->changeReference($argument, $loggerId));
                }
            }
        }
    }

    private function getLoggerWithContext(array $context, ContainerBuilder $container): string
    {
        $md5 = md5(json_encode($context));
        $loggerId = sprintf('logger-with-context.%s', $md5);
        if (!in_array($loggerId, $this->loggers)) {
            //$logger = new DefinitionDecorator('logger-with-context.prototype');
            //$logger->replaceArgument(1, $context);
            $logger = new Definition(LoggerWithContext::class);
            $logger->setArguments([
                new Reference("logger"),
                $context
            ]);
            $container->setDefinition($loggerId, $logger);
            $this->loggers[] = $loggerId;
        }
        return $loggerId;
    }

    private function changeReference(Reference $reference, $serviceId)
    {
        if (method_exists($reference, 'isStrict')) {
            // Stay compatible with Symfony 2
            return new Reference($serviceId, $reference->getInvalidBehavior(), $reference->isStrict(false));
        }

        return new Reference($serviceId, $reference->getInvalidBehavior());
    }
}
