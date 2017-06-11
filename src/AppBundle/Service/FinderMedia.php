<?php

namespace AppBundle\Service;

use AppBundle\Exception\ProtocolSearchInvalidException;
use AppBundle\Model\SearchMedia;
use Psr\Log\LoggerInterface;

abstract class FinderMedia
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

    /**
     * @param SearchMedia $searchMedia
     *
     * @return array
     *
     * @throws ProtocolSearchInvalidException
     */
    abstract public function execute(SearchMedia $searchMedia): array;
}
