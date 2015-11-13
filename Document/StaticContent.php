<?php

namespace DoS\CernelBundle\Document;

use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContent as BaseStaticContent;

class StaticContent extends BaseStaticContent
{
    protected $publishable = false;
}
