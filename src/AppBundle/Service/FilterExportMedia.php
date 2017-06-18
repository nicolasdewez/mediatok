<?php

namespace AppBundle\Service;

use AppBundle\Entity\Media;
use AppBundle\Exception\InvalidFileException;
use AppBundle\Model\ExportMedia;

class FilterExportMedia extends FilterTask
{
    /**
     * @param ExportMedia $exportMedia
     * @param Media[]     $elements
     *
     * @return Media[]
     *
     * @throws InvalidFileException
     */
    public function execute(ExportMedia $exportMedia, array $elements): array
    {
        $this->logger->info(sprintf(
            'Filter elements. filter is #%s#',
            $exportMedia->getFilter('#')
        ));

        $results = [];

        foreach ($elements as $element) {
            if (!$this->isMatchedByFilter($exportMedia, $element->getTitle())) {
                $this->logger->info(sprintf('Media %s not retained: filter not compatible', $element->getTitle()));
                continue;
            }

            // File, mode file and matched
            $results[] = $element;
            $this->logger->info(sprintf('Media %s retained', $element->getTitle()));
        }

        return $results;
    }

    /**
     * @param ExportMedia $exportMedia
     * @param string      $name
     *
     * @return int
     */
    private function isMatchedByFilter(ExportMedia $exportMedia, $name)
    {
        return preg_match(sprintf('#%s#', $exportMedia->getFilter('#')), $name);
    }
}
