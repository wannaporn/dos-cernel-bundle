<?php

namespace DoS\CernelBundle\Model;

interface MediaPathAwareInterface
{
	/**
	 * @return string
	 */
	public function getMediaPath();
}
