<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserParametersRepository")
 */
class UserParameters
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LexaniVideos", mappedBy="userParameters")
     */
    private $lexaniVideos;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $browser;

    public function __construct()
    {
        $this->lexaniVideos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|LexaniVideos[]
     */
    public function getLexaniVideos(): Collection
    {
        return $this->lexaniVideos;
    }

    public function addLexaniVideo(LexaniVideos $lexaniVideo): self
    {
        if (!$this->lexaniVideos->contains($lexaniVideo)) {
            $this->lexaniVideos[] = $lexaniVideo;
            $lexaniVideo->setUserParameters($this);
        }

        return $this;
    }

    public function removeLexaniVideo(LexaniVideos $lexaniVideo): self
    {
        if ($this->lexaniVideos->contains($lexaniVideo)) {
            $this->lexaniVideos->removeElement($lexaniVideo);
            // set the owning side to null (unless already changed)
            if ($lexaniVideo->getUserParameters() === $this) {
                $lexaniVideo->setUserParameters(null);
            }
        }

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function setBrowser(string $browser): self
    {
        $this->browser = $browser;

        return $this;
    }
}
