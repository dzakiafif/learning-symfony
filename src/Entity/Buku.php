<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BukuRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Buku
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $judul;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $pengarang;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $penerbit;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $tahun_terbit;

     /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Pinjam::class, mappedBy="buku")
     */
    private $pinjams;

    public function __construct()
    {
        $this->pinjams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJudul(): ?string
    {
        return $this->judul;
    }

    public function setJudul(string $judul): self
    {
        $this->judul = $judul;

        return $this;
    }

    public function getPengarang(): ?string
    {
        return $this->pengarang;
    }

    public function setPengarang(string $pengarang): self
    {
        $this->pengarang = $pengarang;

        return $this;
    }

    public function getPenerbit(): ?string
    {
        return $this->penerbit;
    }

    public function setPenerbit(string $penerbit): self
    {
        $this->penerbit = $penerbit;

        return $this;
    }

    public function getTahunTerbit(): ?string
    {
        return $this->tahun_terbit;
    }

    public function setTahunTerbit(string $tahun_terbit): self
    {
        $this->tahun_terbit = $tahun_terbit;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
    }

    /**
     * @return Collection|Pinjam[]
     */
    public function getPinjams(): Collection
    {
        return $this->pinjams;
    }

    public function addPinjam(Pinjam $pinjam): self
    {
        if (!$this->pinjams->contains($pinjam)) {
            $this->pinjams[] = $pinjam;
            $pinjam->setBuku($this);
        }

        return $this;
    }

    public function removePinjam(Pinjam $pinjam): self
    {
        if ($this->pinjams->removeElement($pinjam)) {
            // set the owning side to null (unless already changed)
            if ($pinjam->getBuku() === $this) {
                $pinjam->setBuku(null);
            }
        }

        return $this;
    }
}
