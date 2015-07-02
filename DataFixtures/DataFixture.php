<?php

namespace DoS\CernelBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

abstract class DataFixture extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * Container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Alias to default language faker.
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Faker.
     *
     * @var Generator
     */
    protected $fakers;

    /**
     * Default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        if (null !== $container) {
            $this->defaultLocale = $container->getParameter('locale');
            $this->fakers[$this->defaultLocale] = FakerFactory::create($this->defaultLocale);
            $this->faker = $this->fakers[$this->defaultLocale];
        }

        $this->fakers['en_US'] = FakerFactory::create('en_US');
    }

    /**
     * Dispatch an event.
     *
     * @param string $name
     * @param object $object
     */
    protected function dispatchEvent($name, $object)
    {
        return $this->get('event_dispatcher')->dispatch($name, new GenericEvent($object));
    }

    /**
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }
}
