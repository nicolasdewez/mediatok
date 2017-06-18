<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\TypeRepository")
 */
class Type
{
    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned": true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"event_search", "event_export"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $title;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Format", mappedBy="type", cascade={"remove"}, orphanRemoval=true)
     */
    private $formats;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Field", inversedBy="types", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $fields;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Media", mappedBy="type", cascade={"remove"}, orphanRemoval=true)
     */
    private $medias;

    public function __construct()
    {
        $this->active = true;
        $this->formats = new ArrayCollection();
        $this->fields = new ArrayCollection();
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
     * @return Type
     */
    public function setTitle(string $title): Type
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
     * @return Type
     */
    public function setActive(bool $active): Type
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
     * @return Collection
     */
    public function getFormats(): Collection
    {
        return $this->formats;
    }

    /**
     * @param Collection $formats
     *
     * @return Type
     */
    public function setFormats(Collection $formats): Type
    {
        $this->formats = $formats;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    /**
     * @param Collection $fields
     *
     * @return Type
     */
    public function setFields(Collection $fields): Type
    {
        $this->fields = $fields;

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
     * @return Type
     */
    public function setMedias(Collection $medias): Type
    {
        $this->medias = $medias;

        return $this;
    }
}
