<?php

namespace AppBundle\Model;

class File
{
    /** @var string */
    private $name;

    /** @var bool */
    private $directory;

    /** @var File[] */
    private $files;

    /**
     * @param string $name
     * @param bool   $directory
     * @param array  $files
     */
    public function __construct(string $name, bool $directory = false, array $files = [])
    {
        $this->name = $name;
        $this->directory = $directory;
        $this->files = $files;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return File
     */
    public function setName(string $name): File
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDirectory(): bool
    {
        return $this->directory;
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return !$this->directory;
    }

    /**
     * @param bool $directory
     *
     * @return File
     */
    public function setDirectory(bool $directory): File
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param File[] $files
     *
     * @return File
     */
    public function setFiles(array $files): File
    {
        $this->files = $files;

        return $this;
    }
}
