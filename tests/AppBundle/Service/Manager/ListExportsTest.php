<?php

namespace tests\AppBundle\Service\Manager;

use AppBundle\Model\Export;
use AppBundle\Service\Manager\ListExports;
use PHPUnit\Framework\TestCase;

class ListExportsTest extends TestCase
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
        $this->files[] = sprintf('%s/export_20170626_120000.csv', $this->path);

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

    public function testExecute()
    {
        $listExports = new ListExports($this->path);

        $expected = [
            new Export('export_20170626_100000.csv'),
            new Export('export_20170626_110000.pdf'),
            new Export('export_20170626_120000.csv'),
        ];

        $this->assertEquals($expected, $listExports->execute());
    }

    public function testCountByExtension()
    {
        $listExports = new ListExports($this->path);

        $expected = [
            'csv' => 2,
            'pdf' => 1,
        ];

        $this->assertEquals($expected, $listExports->countByExtension());
    }
}
