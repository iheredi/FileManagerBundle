<?php

namespace Artgris\Bundle\FileManagerBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FilemanagerService
{
    /**
     * @var array
     */
    private $artgrisFileManagerConfig;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ParameterBagInterface $parameterBag, ContainerInterface $container)
    {
        $this->artgrisFileManagerConfig = $parameterBag->get('artgris_file_manager');
        $this->container = $container;
    }

    public function getBasePath($queryParameters)
    {
        $conf = $queryParameters['conf'];
        $managerConf = $this->artgrisFileManagerConfig['conf'];
        if (isset($managerConf[$conf]['dir'])) {
            return $managerConf[$conf];
        }

        if (isset($managerConf[$conf]['service'])) {
            $extra = isset($queryParameters['extra']) ? $queryParameters['extra'] : [];
            $conf = $this->container->get($managerConf[$conf]['service'])->getConf($extra);

            return $conf;
        }

        throw new \RuntimeException('Please define a "dir" or a "service" parameter in your config.yml');
    }
}