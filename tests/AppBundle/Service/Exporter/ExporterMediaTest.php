<?php

namespace tests\AppBundle\Service\Exporter;

use AppBundle\Service\Exporter\CsvExporterMedia;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ExporterMediaTest extends TestCase
{
    public function testGetPath()
    {
        $path = '/path/export';
        $exporter = new CsvExporterMedia(new NullLogger(), $path);

        $class = new \ReflectionClass(CsvExporterMedia::class);
        $method = $class->getMethod('getPath');
        $method->setAccessible(true);

        $expected = sprintf('%s/export_%s_%s.csv', $path, date('Ymd'), date('His'));
        $this->assertSame($expected, $method->invokeArgs($exporter, ['csv']));
    }
}
