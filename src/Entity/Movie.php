<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\CustomMovieAction;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

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

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="movie")
     * @ApiSubresource()
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setMovie($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMovie() === $this) {
                $comment->setMovie(null);
            }
        }

        return $this;
    }
}
