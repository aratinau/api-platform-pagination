<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\CustomMovieAction;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "custom-movies-action"={
 *              "method"="GET",
 *              "deserialize"=false,
 *              "path"="/movies/custom-action",
 *              "controller"=CustomMovieAction::class
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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

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
}
