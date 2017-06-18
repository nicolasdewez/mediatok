<?php

namespace AppBundle\Service;

use AppBundle\Entity\Media;

class CsvExporterMedia extends ExporterMedia
{
    const EXPORT_EXTENSION = 'csv';

    /** @var string */
    private $filePath;

    /** @var resource */
    private $handle;

    /**
     * {@inheritdoc}
     */
    public function execute(array $elements)
    {
        $this->filePath = $this->getPath(self::EXPORT_EXTENSION);
        $this->handle = fopen($this->filePath, 'w');
        $this->writeHeaders();
        foreach ($elements as $element) {
            $this->writeLine($element);
        }
        fclose($this->handle);
    }

    protected function writeHeaders()
    {
        fputcsv(
            $this->handle,
            ['Type', 'Format', 'Libellé', 'Supplémentaires'],
            ';'
        );
    }

    /**
     * @param Media $element
     */
    protected function writeLine(Media $element)
    {
        $fields = implode('#', $element->getFields());

        fputcsv(
            $this->handle,
            [
                $element->getType()->getTitle(),
                $element->getFormat()->getTitle(),
                $element->getTitle(),
                $fields,
            ],
            ';'
        );
    }
}
