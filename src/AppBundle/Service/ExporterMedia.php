<?php

namespace AppBundle\Service;

use AppBundle\Entity\Media;
use Psr\Log\LoggerInterface;

abstract class ExporterMedia
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var string */
    protected $path;

    /**
     * @param LoggerInterface $logger
     * @param string          $pathExport
     */
    public function __construct(LoggerInterface $logger, string $pathExport)
    {
        $this->logger = $logger;
        $this->path = $pathExport;
    }

    /**
     * @param Media[] $elements
     */
    abstract public function execute(array $elements);

    /**
     * @param string $extension
     *
     * @return string
     */
    protected function getPath(string $extension): string
    {
        return sprintf('%s/export_%s_%s.%s', $this->path, date('Ymd'), date('His'), $extension);
    }
}
