<?php

namespace AppBundle\Service\Exporter;

use AppBundle\Exception\InvalidModeExportException;
use AppBundle\Model\ExportMedia;

class ChoiceExporterMedia
{
    /** @var CsvExporterMedia */
    private $csvExporterMedia;

    /** @var PdfExporterMedia */
    private $pdfExporterMedia;

    /**
     * @param CsvExporterMedia $csvExporterMedia
     * @param PdfExporterMedia $pdfExporterMedia
     */
    public function __construct(CsvExporterMedia $csvExporterMedia, PdfExporterMedia $pdfExporterMedia)
    {
        $this->csvExporterMedia = $csvExporterMedia;
        $this->pdfExporterMedia = $pdfExporterMedia;
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

        if (ExportMedia::MODE_PDF === $choice) {
            return $this->pdfExporterMedia;
        }

        throw new InvalidModeExportException(sprintf('Mode %s is not valid for export.', $choice));
    }
}
