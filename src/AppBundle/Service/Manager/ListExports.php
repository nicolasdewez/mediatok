<?php

namespace AppBundle\Service\Manager;

use AppBundle\Model\Export;

class ListExports
{
    /** @var string */
    protected $path;

    /**
     * @param string $pathExport
     */
    public function __construct(string $pathExport)
    {
        $this->path = $pathExport;
    }

    /**
     * @return Export[]
     */
    public function execute(): array
    {
        $elements = [];

        $files = array_diff(scandir($this->path), ['.', '..']);
        foreach ($files as $file) {
            $elements[] = new Export($file);
        }

        return $elements;
    }

    /**
     * @return array
     */
    public function countByExtension(): array
    {
        $exports = [];
        foreach ($this->execute() as $export) {
            $extension = $export->getExtension();
            if (!isset($exports[$extension])) {
                $exports[$extension] = 0;
            }

            ++$exports[$extension];
        }

        return $exports;
    }
}
