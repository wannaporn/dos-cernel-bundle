<?php

namespace DoS\CernelBundle\Document;

use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock as BaseImagineBlock;

class ImagineBlock extends BaseImagineBlock
{
    protected $publishable = false;
}
