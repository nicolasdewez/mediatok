<?php

namespace AppBundle\Service;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class DeleteExport
{
    /** @var string */
    private $exportPath;

    /**
     * @param string $exportPath
     */
    public function __construct(string $exportPath)
    {
        $this->exportPath = $exportPath;
    }

    /**
     * @param string $file
     *
     * @throws FileNotFoundException
     */
    public function process(string $file)
    {
        $path = sprintf('%s/%s', $this->exportPath, $file);
        if (!file_exists($path)) {
            throw new FileNotFoundException(sprintf('File %s not found.', $file));
        }

        unlink($path);
    }
}
