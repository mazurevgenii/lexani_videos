<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=255)
     */
    private $Link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $YouTube_Link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $YouTube_Link_old;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Title_old;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Description_old;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Thumbnail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Thumbnail_old;

    /**
     * @ORM\Column(type="text")
     */
    private $IP;

    /**
     * @ORM\Column(type="text")
     */
    private $Browser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->Link;
    }

    public function setLink(string $Link): self
    {
        $this->Link = $Link;

        return $this;
    }

    public function getYouTubeLink(): ?string
    {
        return $this->YouTube_Link;
    }

    public function setYouTubeLink(string $YouTube_Link): self
    {
        $this->YouTube_Link = $YouTube_Link;

        return $this;
    }

    public function getYouTubeLinkOld(): ?string
    {
        return $this->YouTube_Link_old;
    }

    public function setYouTubeLinkOld(string $YouTube_Link_old): self
    {
        $this->YouTube_Link_old = $YouTube_Link_old;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getTitleOld(): ?string
    {
        return $this->Title_old;
    }

    public function setTitleOld(?string $Title_old): self
    {
        $this->Title_old = $Title_old;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDescriptionOld(): ?string
    {
        return $this->Description_old;
    }

    public function setDescriptionOld(?string $Description_old): self
    {
        $this->Description_old = $Description_old;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->Thumbnail;
    }

    public function setThumbnail(?string $Thumbnail): self
    {
        $this->Thumbnail = $Thumbnail;

        return $this;
    }

    public function getThumbnailOld(): ?string
    {
        return $this->Thumbnail_old;
    }

    public function setThumbnailOld(?string $Thumbnail_old): self
    {
        $this->Thumbnail_old = $Thumbnail_old;

        return $this;
    }

    public function getIP(): ?string
    {
        return $this->IP;
    }

    public function setIP(string $IP): self
    {
        $this->IP = $IP;

        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->Browser;
    }

    public function setBrowser(string $Browser): self
    {
        $this->Browser = $Browser;

        return $this;
    }
}
