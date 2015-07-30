<?php

namespace DoS\CernelBundle\Controller;

use Liip\ImagineBundle\Controller\ImagineController as BaseImagineController;
use Symfony\Component\HttpFoundation\Request;

class ImagineController extends BaseImagineController
{
    /**
     * @var string
     */
    protected $filterPrefix = 'size_';

    /**
     * @param string $filter
     *
     * @return string
     */
    private function flexibleFilterName($filter)
    {
        $filter = str_replace('-', '_', $filter);

        // numeric only
        if (preg_match('/^([0-9]+)$/', $filter)) {
            $filter = sprintf('%s%sx%s',$this->filterPrefix, $filter, $filter);
            // numeric x numeric
        } elseif (preg_match('/^([0-9]+)x([0-9]+)$/', $filter)) {
            $filter = sprintf('%s%s', $this->filterPrefix, $filter);
        } else {
            // nothing
        }

        return $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function filterAction(Request $request, $path, $filter)
    {
        return parent::filterAction($request, $path, $this->flexibleFilterName($filter));
    }

    /**
     * {@inheritdoc}
     */
    public function filterRuntimeAction(Request $request, $hash, $path, $filter)
    {
        return parent::filterRuntimeAction($request, $hash, $path, $this->flexibleFilterName($filter));
    }
}
