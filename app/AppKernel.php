<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new wlatanowicz\AppBundle\wlatanowiczAppBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new \wlatanowicz\DevBundle\DevBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function getSessionsDir()
    {
        return dirname(__DIR__).'/var/sessions';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $configFile = php_uname('s') == 'Darwin'
            ? 'config_mac.yml'
            : 'config.yml';
        $loader->load($this->getRootDir() . '/config/' . $configFile);
    }

    protected function getKernelParameters()
    {
        return array_merge(
            parent::getKernelParameters(),
            [
                "kernel.sessions_dir" => $this->getSessionsDir(),
            ]
        );
    }
}
