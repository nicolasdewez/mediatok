<?php

namespace AppBundle\Service\Filter;

use Psr\Log\LoggerInterface;

abstract class FilterTask
{
    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
