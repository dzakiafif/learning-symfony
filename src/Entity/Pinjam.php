<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PinjamRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Pinjam
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Anggota", inversedBy="pinjams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $anggota;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Buku", inversedBy="pinjams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buku;

    /**
     * @ORM\Column(type="date")
     */
    private $tanggal_pinjam;

    /**
     * @ORM\Column(type="date")
     */
    private $tanggal_kembali;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnggota(): ?Anggota
    {
        return $this->anggota;
    }

    public function setAnggota(?Anggota $anggota): self
    {
        $this->anggota = $anggota;

        return $this;
    }

    public function getBuku(): ?Buku
    {
        return $this->buku;
    }

    public function setBuku(?Buku $buku): self
    {
        $this->buku = $buku;

        return $this;
    }

    public function getTanggalPinjam(): ?\DateTimeInterface
    {
        return $this->tanggal_pinjam;
    }

    public function setTanggalPinjam(\DateTimeInterface $tanggal_pinjam): self
    {
        $this->tanggal_pinjam = $tanggal_pinjam;

        return $this;
    }

    public function getTanggalKembali(): ?\DateTimeInterface
    {
        return $this->tanggal_kembali;
    }

    public function setTanggalKembali(\DateTimeInterface $tanggal_kembali): self
    {
        $this->tanggal_kembali = $tanggal_kembali;

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
}
