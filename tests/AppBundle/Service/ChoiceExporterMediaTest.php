<?php

namespace tests\AppBundle\Service;

use AppBundle\Model\ExportMedia;
use AppBundle\Service\ChoiceExporterMedia;
use AppBundle\Service\CsvExporterMedia;
use AppBundle\Service\PdfExporterMedia;
use PHPUnit\Framework\TestCase;

class ChoiceExporterMediaTest extends TestCase
{
    public function testExecute()
    {
        $csv = $this->createMock(CsvExporterMedia::class);
        $pdf = $this->createMock(PdfExporterMedia::class);

        $choiceExporterMedia = new ChoiceExporterMedia($csv, $pdf);

        $this->assertSame($csv, $choiceExporterMedia->execute(ExportMedia::MODE_CSV));
        $this->assertSame($pdf, $choiceExporterMedia->execute(ExportMedia::MODE_PDF));
    }
}
