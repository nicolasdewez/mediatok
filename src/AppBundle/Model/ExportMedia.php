<?php

namespace AppBundle\Model;

use AppBundle\Entity\Type;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

class ExportMedia implements TaskInterface
{
    const MODE_CSV = 'csv';
    const MODE_PDF = 'pdf';

    /**
     * @var string
     *
     * @Serializer\Groups({"event_export"})
     */
    private $mode;

    /**
     * @var string
     *
     * @Serializer\Groups({"event_export"})
     */
    private $filter;

    /**
     * @var Type[]
     *
     * @Serializer\Groups({"event_export"})
     */
    private $types;

    public function __construct()
    {
        $this->types = [];
    }

    /**
     * @return string
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @return ExportMedia
     */
    public function setMode(string $mode): ExportMedia
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @param string $escape
     *
     * @return string
     */
    public function getFilter(string $escape = ''): ?string
    {
        if ('' === $escape) {
            return $this->filter;
        }

        return str_replace($escape, sprintf('\%s', $escape), $this->filter);
    }

    /**
     * @param string $filter
     *
     * @return ExportMedia
     */
    public function setFilter(string $filter): ExportMedia
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return Type[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param Collection|Type[] $types
     *
     * @return ExportMedia
     */
    public function setTypes($types): ExportMedia
    {
        if ($types instanceof Collection) {
            $this->types = $types->toArray();

            return $this;
        }

        $this->types = $types;

        return $this;
    }
}
