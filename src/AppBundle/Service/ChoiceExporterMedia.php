<?php

namespace AppBundle\Service;

use AppBundle\Exception\InvalidModeExportException;
use AppBundle\Model\ExportMedia;

class ChoiceExporterMedia
{
    /** @var CsvExporterMedia */
    private $csvExporterMedia;

    /**
     * @param CsvExporterMedia $csvExporterMedia
     */
    public function __construct(CsvExporterMedia $csvExporterMedia)
    {
        $this->csvExporterMedia = $csvExporterMedia;
    }

    /**
     * @param string $choice
     *
     * @return ExporterMedia
     *
     * @throws InvalidModeExportException
     */
    public function execute(string $choice): ExporterMedia
    {
        if (ExportMedia::MODE_CSV === $choice) {
            return $this->csvExporterMedia;
        }

        throw new InvalidModeExportException(sprintf('Mode %s is not valid for export.', $choice));
    }
}
