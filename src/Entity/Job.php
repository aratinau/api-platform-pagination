<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"pagination_enabled"=true},
 *     itemOperations={
 *          "get",
 *          "custom-subresource-job-employees"={
 *              "method"="GET",
 *              "deserialize"=false,
 *              "path"="/jobs/{id}/employees",
 *              "normalization_context"={"groups" = "normalization-custom-subresource-job-employees"}
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"normalization-custom-subresource-job-employees"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"normalization-custom-subresource-job-employees"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"normalization-custom-subresource-job-employees"})
     */
    private $isPublished;

    /**
     * @ORM\OneToMany(targetEntity=Employee::class, mappedBy="job")
     * @Groups({"normalization-custom-subresource-job-employees"})
     */
    private $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection|Employee[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setJob($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getJob() === $this) {
                $employee->setJob(null);
            }
        }

        return $this;
    }
}
