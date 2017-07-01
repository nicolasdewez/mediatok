<?php

namespace tests\AppBundle\Service\Manager;

use AppBundle\Service\Manager\ShowExport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ShowExportTest extends TestCase
{
    /** @var string */
    private $path;

    /** @var array */
    private $files = [];

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->path = '/tmp';

        $this->files[] = sprintf('%s/export_20170626_100000.csv', $this->path);
        $this->files[] = sprintf('%s/export_20170626_110000.pdf', $this->path);
        $this->files[] = sprintf('%s/export_20170626_120000.txt', $this->path);
        $this->files[] = sprintf('%s/export_20170626_130000', $this->path);

        foreach ($this->files as $file) {
            touch($file);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        foreach ($this->files as $file) {
            if (!file_exists($file)) {
                continue;
            }
            unlink($file);
        }
    }

    public function testGetContentType()
    {
        $showExport = new ShowExport($this->path);

        $class = new \ReflectionClass(ShowExport::class);
        $method = $class->getMethod('getContentType');
        $method->setAccessible(true);

        $this->assertSame(ShowExport::MIME_CSV, $method->invokeArgs($showExport, [$this->files[0]]));
        $this->assertSame(ShowExport::MIME_PDF, $method->invokeArgs($showExport, [$this->files[1]]));
        $this->assertSame(ShowExport::MIME_CSV, $method->invokeArgs($showExport, [$this->files[2]]));
        $this->assertSame(ShowExport::MIME_CSV, $method->invokeArgs($showExport, [$this->files[3]]));
    }

    public function testGetResponse()
    {
        $showExport = new ShowExport($this->path);

        $file = 'export_20170626_100000.csv';

        $expected = new Response('');
        $expected->headers->set('Content-Type', ShowExport::MIME_CSV);
        $expected->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $file));

        $this->assertEquals($expected, $showExport->getResponse($file));
    }
}
