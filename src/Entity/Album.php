<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AlbumRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 */
class Album
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"normalization-albums-by-artist"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"normalization-albums-by-artist"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"normalization-albums-by-artist"})
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=Artist::class, inversedBy="albums")
     */
    private $artist;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }
}
