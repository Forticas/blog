<?php

namespace App\EventSubscriber;

use App\Entity\Config;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Contracts\Cache\CacheInterface;

class AfterChangeConfigSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private CacheInterface $cache
    )
    {
    }

    public function clearCache(AfterEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Config) {
            $this->cache->delete('website_config');
        }
    }


    public static function getSubscribedEvents()
    {
        return [
            AfterEntityUpdatedEvent::class => ['clearCache']
        ];
    }
}
