<?php

namespace DoS\CernelBundle\Document;

use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock as BaseSlideshowBlock;

class SlideshowBlock extends BaseSlideshowBlock
{
    /**
     * @var string
     */
    protected $filter;


    /**
     * Sets the Imagine filter which is going to be used
     *
     * @param string $filter
     *
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Get the Imagine filter
     *
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }
}
