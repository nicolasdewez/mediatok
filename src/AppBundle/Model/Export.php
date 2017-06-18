<?php

namespace AppBundle\Model;

class Export
{
    const BASENAME = '#export_(?P<year>[0-9]{4})(?P<month>[0-9]{2})(?P<day>[0-9]{2})_(?P<hours>[0-9]{2})(?P<minutes>[0-9]{2})(?P<seconds>[0-9]{2}).(?P<extension>[a-z]{3,})#';

    /** @var string */
    private $name;

    /** @var string */
    private $date;

    /** @var string */
    private $time;

    /** @var string */
    private $extension;

    /**
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $matches = [];
        preg_match(self::BASENAME, $filename, $matches);

        $this->date = sprintf('%s/%s/%s', $matches['day'], $matches['month'], $matches['year']);
        $this->time = sprintf('%s:%s:%s', $matches['hours'], $matches['minutes'], $matches['seconds']);
        $this->extension = $matches['extension'];
        $this->name = $filename;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }
}
