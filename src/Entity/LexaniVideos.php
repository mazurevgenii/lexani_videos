<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LexaniVideosRepository")
 */
class LexaniVideos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Url()
     * @Assert\NotBlank()
     */
    private $youtubeLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Url()
     * @Assert\NotBlank()
     */
    private $thumbnails;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $parseType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserParameters", inversedBy="lexaniVideos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userParameters;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYoutubeLink(): ?string
    {
        return $this->youtubeLink;
    }

    public function setYoutubeLink(string $youtubeLink): self
    {
        $this->youtubeLink = $youtubeLink;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getThumbnails(): ?string
    {
        return $this->thumbnails;
    }

    public function setThumbnails(string $thumbnails): self
    {
        $this->thumbnails = $thumbnails;

        return $this;
    }

    public function getParseType(): ?string
    {
        return $this->parseType;
    }

    public function setParseType(string $parseType): self
    {
        $this->parseType = $parseType;

        return $this;
    }

    public function getUserParameters(): ?UserParameters
    {
        return $this->userParameters;
    }

    public function setUserParameters(?UserParameters $userParameters): self
    {
        $this->userParameters = $userParameters;

        return $this;
    }
}
