<?php

namespace DoS\CernelBundle\Form\Type;

use DoS\CernelBundle\Doctrine\Phpcr\ManagerHelper;
use DoS\CernelBundle\Model\MediaInterface;
use Sylius\Bundle\MediaBundle\Form\Type\ImageType as BaseImageType;
use Symfony\Cmf\Bundle\MediaBundle\File\UploadFileHelperDoctrine;
use Symfony\Cmf\Bundle\MediaBundle\File\UploadFileHelperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ImageType extends BaseImageType
{
    /**
     * @var UploadFileHelperInterface|UploadFileHelperDoctrine
     */
    protected $uploadFileHelper;

    /**
     * @var string
     */
    protected $mediaRoot = '/cms/medias';

    /**
     * @param UploadFileHelperInterface $uploadFileHelper
     */
    public function setUploadFileHelper(UploadFileHelperInterface $uploadFileHelper = null)
    {
        $this->uploadFileHelper = $uploadFileHelper;
    }

    /**
     * @param string $mediaRoot
     */
    public function setMediaRoot($mediaRoot)
    {
        $this->mediaRoot = $mediaRoot;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if (!$this->uploadFileHelper) {
            return;
        }

        $builder->get('media')
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
                /** @var MediaInterface $media */
                if ($parent = $event->getForm()->getParent()) {
                    $media = $parent->getData();

                    if ($path = $media->getPath()) {
                        $dirs = ManagerHelper::mkdirs($this->documentManager, $this->mediaRoot . $path);
                        $this->uploadFileHelper->setRootPath(end($dirs)->getId());
                    }
                }
            })
            ->addEventListener(FormEvents::POST_SUBMIT, function() {
                // reset to root base
                $this->uploadFileHelper->setRootPath($this->mediaRoot);
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'dos_image';
    }
}
