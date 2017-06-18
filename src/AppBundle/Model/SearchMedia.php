<?php

namespace AppBundle\Model;

use AppBundle\Entity\Format;
use AppBundle\Entity\Type;
use Symfony\Component\Serializer\Annotation as Serializer;

class SearchMedia implements Task
{
    const PROTOCOL_SFTP = 'ssh2.sftp';

    const SAVE_MODE_NOTHING = 1;
    const SAVE_MODE_UPDATE = 2;

    const FILE_MODE_FILES = 1;
    const FILE_MODE_DIRECTORIES = 2;

    /**
     * @var string
     *
     * @Serializer\Groups({"event_search"})
     */
    private $protocol;

    /**
     * @var string
     *
     * @Serializer\Groups({"event_search"})
     */
    private $host;

    /**
     * @var int
     *
     * @Serializer\Groups({"event_search"})
     */
    private $port;

    /**
     * @var string
     *
     * @Serializer\Groups({"event_search"})
     */
    private $username;

    /**
     * @var string
     *
     * @Serializer\Groups({"event_search"})
     */
    private $password;

    /**
     * @var string
     *
     * @Serializer\Groups({"event_search"})
     */
    private $directory;

    /**
     * @var bool
     *
     * @Serializer\Groups({"event_search"})
     */
    private $recursive;

    /**
     * @var string
     *
     * @Serializer\Groups({"event_search"})
     */
    private $filter;

    /**
     * @var int
     *
     * @Serializer\Groups({"event_search"})
     */
    private $fileMode;

    /**
     * @var int
     *
     * @Serializer\Groups({"event_search"})
     */
    private $saveMode;

    /**
     * @var Type
     *
     * @Serializer\Groups({"event_search"})
     */
    private $type;

    /**
     * @var Format
     *
     * @Serializer\Groups({"event_search"})
     */
    private $format;

    /**
     * @return string
     */
    public function getProtocol(): ?string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     *
     * @return SearchMedia
     */
    public function setProtocol(string $protocol): SearchMedia
    {
        $this->protocol = $protocol;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @param string $host
     *
     * @return SearchMedia
     */
    public function setHost(string $host): SearchMedia
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @param int $port
     *
     * @return SearchMedia
     */
    public function setPort(int $port): SearchMedia
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return SearchMedia
     */
    public function setUsername(string $username): SearchMedia
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return SearchMedia
     */
    public function setPassword(string $password): SearchMedia
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     *
     * @return SearchMedia
     */
    public function setDirectory(string $directory): SearchMedia
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRecursive(): ?bool
    {
        return $this->recursive;
    }

    /**
     * @param bool $recursive
     *
     * @return SearchMedia
     */
    public function setRecursive(bool $recursive): SearchMedia
    {
        $this->recursive = $recursive;

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
     * @return SearchMedia
     */
    public function setFilter(string $filter): SearchMedia
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return int
     */
    public function getFileMode(): ?int
    {
        return $this->fileMode;
    }

    /**
     * @param int $fileMode
     *
     * @return SearchMedia
     */
    public function setFileMode(int $fileMode): SearchMedia
    {
        $this->fileMode = $fileMode;

        return $this;
    }

    /**
     * @return int
     */
    public function getSaveMode(): ?int
    {
        return $this->saveMode;
    }

    /**
     * @param int $saveMode
     *
     * @return SearchMedia
     */
    public function setSaveMode(int $saveMode): SearchMedia
    {
        $this->saveMode = $saveMode;

        return $this;
    }

    /**
     * @return Type
     */
    public function getType(): ?Type
    {
        return $this->type;
    }

    /**
     * @param Type $type
     *
     * @return SearchMedia
     */
    public function setType(Type $type): SearchMedia
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Format
     */
    public function getFormat(): ?Format
    {
        return $this->format;
    }

    /**
     * @param Format $format
     *
     * @return SearchMedia
     */
    public function setFormat(Format $format): SearchMedia
    {
        $this->format = $format;

        return $this;
    }
}
