<?php

namespace tests\AppBundle\Service\Manager;

use AppBundle\Service\Manager\DeleteExport;
use PHPUnit\Framework\TestCase;

class DeleteExportTest extends TestCase
{
    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\FileNotFoundException
     */
    public function testProcessFileNotFound()
    {
        $deleteExport = new DeleteExport('/path/not/exists');
        $deleteExport->process('file.csv');
    }

    public function testProcess()
    {
        $path = '/tmp';
        $file = 'mediatok_export_test_delete.csv';
        $completePath = sprintf('%s/%s', $path, $file);

        touch($completePath);
        $deleteExport = new DeleteExport($path);
        $deleteExport->process($file);

        $this->assertFalse(file_exists($completePath));
    }
}
