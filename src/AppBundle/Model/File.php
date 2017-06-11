<?php

namespace AppBundle\Model;

class File
{
    /** @var string */
    private $name;

    /** @var bool */
    private $isDirectory;

    /** @var File[] */
    private $files;

    /**
     * @param string $name
     * @param bool   $isDirectory
     * @param array  $files
     */
    public function __construct(string $name, bool $isDirectory = false, array $files = [])
    {
        $this->name = $name;
        $this->isDirectory = $isDirectory;
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
        return $this->isDirectory;
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return !$this->isDirectory;
    }

    /**
     * @param bool $isDirectory
     *
     * @return File
     */
    public function setIsDirectory(bool $isDirectory): File
    {
        $this->isDirectory = $isDirectory;

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
