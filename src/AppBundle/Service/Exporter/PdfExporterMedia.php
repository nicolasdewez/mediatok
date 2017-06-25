<?php

namespace AppBundle\Service\Exporter;

use AppBundle\Entity\Media;

class PdfExporterMedia extends ExporterMedia
{
    const EXPORT_EXTENSION = 'pdf';

    const HEIGHT_LINE = 6;
    const WIDTH_COLUMNS = [50, 50, 90];

    /** @var string */
    private $filePath;

    /** @var \FPDF */
    private $handle;

    /**
     * {@inheritdoc}
     */
    public function execute(array $elements)
    {
        $this->filePath = $this->getPath(self::EXPORT_EXTENSION);

        $this->initialize();

        $this->writeHeaders();
        foreach ($elements as $element) {
            $this->writeLine($element);
        }

        file_put_contents($this->filePath, $this->handle->Output('S'));
    }

    private function initialize()
    {
        $this->handle = new \FPDF();
        $this->handle->AddPage();
    }

    private function writeHeaders()
    {
        $this->handle->SetFont('Arial', 'B', 10);
        $this->handle->Cell(self::WIDTH_COLUMNS[0], self::HEIGHT_LINE, 'Type', 1, 0, 'C');
        $this->handle->Cell(self::WIDTH_COLUMNS[1], self::HEIGHT_LINE, 'Format', 1, 0, 'C');
        $this->handle->Cell(self::WIDTH_COLUMNS[2], self::HEIGHT_LINE, 'Titre', 1, 1, 'C');
    }

    /**
     * @param Media $element
     */
    protected function writeLine(Media $element)
    {
        $this->handle->SetFont('Arial', '', 9);
        $this->handle->Cell(self::WIDTH_COLUMNS[0], self::HEIGHT_LINE, utf8_decode($element->getType()->getTitle()), 1);
        $this->handle->Cell(self::WIDTH_COLUMNS[1], self::HEIGHT_LINE, utf8_decode($element->getFormat()->getTitle()), 1);
        $this->handle->Cell(self::WIDTH_COLUMNS[2], self::HEIGHT_LINE, utf8_decode($element->getTitle()), 1, 1);
    }
}
