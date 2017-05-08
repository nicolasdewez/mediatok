<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Table
 * @ORM\Entity
 */
class Format
{
    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned": true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"api_get"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"api_get"})
     */
    private $title;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Serializer\Groups({"api_get"})
     */
    private $active;

    /**
     * @var Type
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Type", inversedBy="formats")
     */
    private $type;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Media", mappedBy="format", cascade={"remove"}, orphanRemoval=true)
     */
    private $medias;

    public function __construct()
    {
        $this->active = true;
        $this->medias = new ArrayCollection();
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
     * @return Format
     */
    public function setTitle(string $title): Format
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
     * @param bool $active
     *
     * @return Format
     */
    public function setActive(bool $active): Format
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @param Type $type
     *
     * @return Format
     */
    public function setType(Type $type): Format
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    /**
     * @param Collection $medias
     *
     * @return Format
     */
    public function setMedias(Collection $medias): Format
    {
        $this->medias = $medias;

        return $this;
    }
}
