<?php

namespace DoS\CernelBundle\DataFixtures\PHPCR;

use Doctrine\Common\Persistence\ObjectManager;
use DoS\CernelBundle\DataFixtures\DataFixture;
use PHPCR\Util\NodeHelper;

class LoadBlocksData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();

        $basepath = $this->container->getParameter('cmf_block.persistence.phpcr.block_basepath');
        NodeHelper::createPath($session, $basepath);

        $parent = $manager->find(null, $basepath);
        $repository = $this->container->get('sylius.repository.simple_block');

        $contactBlock = $repository->createNew();
        $contactBlock->setParentDocument($parent);
        $contactBlock->setName('contact');
        $contactBlock->setTitle('Contact us');
        $contactBlock->setBody('<p>Call us '.$this->faker->phoneNumber.'!</p><p>'.$this->faker->paragraph.'</p>');

        $manager->persist($contactBlock);

        for ($i = 1; $i <= 3; $i++) {
            $block = $repository->createNew();
            $block->setParentDocument($parent);
            $block->setName('block-'.$i);
            $block->setTitle(ucfirst($this->faker->word));
            $block->setBody($this->faker->paragraph);

            $manager->persist($block);
        }

        $repository = $this->container->get('sylius.repository.string_block');

        $welcomeText = $repository->createNew();
        $welcomeText->setParentDocument($parent);
        $welcomeText->setName('welcome-text');
        $welcomeText->setBody('Welcome to Sylius e-commerce');

        $manager->persist($welcomeText);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
