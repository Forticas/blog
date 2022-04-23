<?php

namespace App\EventSubscriber;

use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Symfony\Component\String\Slugger\SluggerInterface;

class SlugifySubscriber  implements EventSubscriberInterface
{
    public function __construct(
        private SluggerInterface $slugger
    )
    {
    }
    //catch object prePersist event, verify if slug proprity exists and if not, generate it
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }
    public function prePersist($event)
    {
        $object = $event->getObject();
        if (!property_exists($object, 'slug')) {
            return;
        }
        if (null === $object->getSlug()) {
            if($object instanceof Category || $object instanceof Tag) {
                $object->setSlug(strtolower($this->slugger->slug($object->getName())));
                return;
            }
            $object->setSlug(strtolower($this->slugger->slug($object->getTitle()) . '-' . uniqid()));

        }
    }

}