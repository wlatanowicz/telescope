<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Job;

abstract class AbstractJob
{
    public function start(array $namedParameters)
    {
        $reflection = new \ReflectionMethod(get_class($this), "execute");
        $parameters = $reflection->getParameters();

        $parametersArray = [];

        foreach ($parameters as $parameter) {
            /**
             * @var $parameter \ReflectionParameter
             */
            $parameterName = $parameter->getName();
            if (array_key_exists($parameterName, $namedParameters)) {
                $parametersArray[$parameter->getPosition()] = $namedParameters[$parameterName];
            }
        }

        $method = 'execute';
        $this->{$method}(...$parametersArray);
    }

    //protected abstract function execute(...);
}
