<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\MediaRepository")
 */
class Media
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", options={"unsigned": true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $fields;

    /**
     * @var Type
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Type", inversedBy="medias")
     */
    private $type;

    /**
     * @var Format
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Format", inversedBy="medias")
     */
    private $format;

    public function __construct()
    {
        $this->fields = [];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return Media
     */
    public function setTitle(string $title): Media
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param array $fields
     *
     * @return Media
     */
    public function setFields(array $fields): Media
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return Type|null
     */
    public function getType(): ?Type
    {
        return $this->type;
    }

    /**
     * @param Type $type
     *
     * @return Media
     */
    public function setType(Type $type): Media
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Format|null
     */
    public function getFormat(): ?Format
    {
        return $this->format;
    }

    /**
     * @param Format $format
     *
     * @return Media
     */
    public function setFormat(Format $format): Media
    {
        $this->format = $format;

        return $this;
    }
}
