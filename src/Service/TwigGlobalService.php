<?php

namespace App\Service;

use App\Entity\Config;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class TwigGlobalService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CacheInterface         $cache
    )
    {
    }

    public function website()
    {
        return $this->cache->get('website_config', function () {
            $config_array = [];
            $config_list= $this->entityManager->getRepository(Config::class)->findAll();
            foreach ($config_list as $config) {
                $config_array[$config->getName()] = $config->getValue();
            }
            return $config_array;
        });
    }
}