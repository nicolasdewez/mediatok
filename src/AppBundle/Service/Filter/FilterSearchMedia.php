<?php

namespace AppBundle\Service\Filter;

use AppBundle\Exception\InvalidFileException;
use AppBundle\Model\File;
use AppBundle\Model\SearchMedia;

class FilterSearchMedia extends FilterTask
{
    /**
     * @param SearchMedia $searchMedia
     * @param File[]      $elements
     *
     * @return array
     *
     * @throws InvalidFileException
     */
    public function execute(SearchMedia $searchMedia, array $elements): array
    {
        $this->logger->info(sprintf(
            'Filter elements. File mode is %d, filter is #%s#',
            $searchMedia->getFileMode(),
            $searchMedia->getFilter('#')
        ));

        $results = [];

        foreach ($elements as $element) {
            // File but mode directory
            if (true === $element->isFile() && SearchMedia::FILE_MODE_FILES !== $searchMedia->getFileMode()) {
                $this->logger->info(sprintf('File %s not retained: mode not compatible', $element->getName()));
                continue;
            }

            // File
            if (true === $element->isFile()) {
                // File, mode file but not matched
                if (!$this->isMatchedByFilter($searchMedia, $element->getName())) {
                    $this->logger->info(sprintf('File %s not retained: filter not compatible', $element->getName()));
                    continue;
                }

                // File, mode file and matched
                $results[] = $element->getName();
                $this->logger->info(sprintf('File %s retained', $element->getName()));

                continue;
            }

            if (true !== $element->isDirectory()) {
                throw new InvalidFileException(sprintf('Element %s is not file, not directory.', $element->getName()));
            }

            // Directory
            if (SearchMedia::FILE_MODE_FILES === $searchMedia->getFileMode()) {
                $this->logger->info(sprintf('Directory %s not retained: mode not compatible', $element->getName()));

                // Add elements of directory
                $results = array_merge($results, $this->execute($searchMedia, $element->getFiles()));

                continue;
            }

            // Directory, mode directory but not matched
            if (!$this->isMatchedByFilter($searchMedia, $element->getName())) {
                $this->logger->info(sprintf('Directory %s not retained: filter not compatible', $element->getName()));

                // Add elements of directory
                $results = array_merge($results, $this->execute($searchMedia, $element->getFiles()));

                continue;
            }

            // Directory, mode directory and matched
            $results[] = $element->getName();
            $this->logger->info(sprintf('Directory %s retained', $element->getName()));

            // Add elements of directory
            $results = array_merge($results, $this->execute($searchMedia, $element->getFiles()));
        }

        return $results;
    }

    /**
     * @param SearchMedia $searchMedia
     * @param string      $name
     *
     * @return int
     */
    private function isMatchedByFilter(SearchMedia $searchMedia, $name)
    {
        return preg_match(sprintf('#%s#', $searchMedia->getFilter('#')), $name);
    }
}
