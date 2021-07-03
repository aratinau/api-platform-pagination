<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\CustomMovieAction;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "custom-movies-action"={
 *              "method"="GET",
 *              "deserialize"=false,
 *              "path"="/movies/custom-action",
 *              "controller"=CustomMovieAction::class
 *          },
 *          "custom-action-using-dataprovider"={
 *              "method"="GET",
 *              "deserialize"=false,
 *              "path"="/movies/custom-action-using-dataprovider",
 *              "normalization_context"={"groups" = "normalization-custom-action-using-dataprovider"}
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"normalization-custom-action-using-dataprovider"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"normalization-custom-action-using-dataprovider"})
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"normalization-custom-action-using-dataprovider"})
     */
    private $isPublished;

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

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}
