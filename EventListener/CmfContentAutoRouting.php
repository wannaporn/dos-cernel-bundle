<?php

namespace DoS\CernelBundle\EventListener;

use Sylius\Component\Resource\Event\ResourceEvent;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Component\DependencyInjection\ContainerAware;

class CmfContentAutoRouting extends ContainerAware
{
    /**
     * {@inheritdoc}
     */
    public function postPersist(ResourceEvent $event)
    {
        $object = $event->getSubject();

        if (!$object instanceof StaticContent) {
            return;
        }

        $prefix = $this->container->getParameter('cmf_routing.dynamic.persistence.phpcr.route_basepath');
        $path = sprintf('%s/%s', $prefix, $object->getName());
        $dm = $this->container->get('sylius.manager.route');

        if ($dm->find(null, $path)) {
            return;
        }

        /** @var Route $document */
        $document = $this->container->get('sylius.factory.route')->createNew();
        $document->setParentDocument($dm->find(null, $prefix));
        $document->setName($object->getName());
        $document->setContent($object);

        $dm->persist($document);
        $dm->flush($document);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate(ResourceEvent $event)
    {
        $this->postPersist($event);
    }

    /**
     * {@inheritdoc}
     */
    public function postRemove(ResourceEvent $event)
    {
        $object = $event->getSubject();

        if (!$object instanceof StaticContent) {
            return;
        }

        $prefix = $this->container->getParameter('cmf_routing.dynamic.persistence.phpcr.route_basepath');
        $path = sprintf('%s/%s', $prefix, $object->getName());
        $dm = $this->container->get('sylius.manager.route');

        if ($route = $dm->find(null, $path)) {
            $dm->remove($route);
            $dm->flush($route);
        }
    }
}
