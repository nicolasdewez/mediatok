<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\FieldRepository")
 */
class Field
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
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Type", mappedBy="fields", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $types;

    public function __construct()
    {
        $this->active = true;
        $this->types = new ArrayCollection();
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
     * @return Field
     */
    public function setTitle(string $title): Field
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
     * @return Field
     */
    public function setActive(bool $active): Field
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
    public function getTypes(): Collection
    {
        return $this->types;
    }

    /**
     * @param Collection $types
     *
     * @return Field
     */
    public function setFields(Collection $types): Field
    {
        $this->types = $types;

        return $this;
    }
}
