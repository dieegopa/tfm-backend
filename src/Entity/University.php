<?php

namespace App\Entity;

use App\Repository\UniversityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniversityRepository::class)]
class University
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $image_url = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'university', targetEntity: Degree::class, orphanRemoval: true)]
    private Collection $degrees;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'universities')]
    private Collection $users;

    public function __construct()
    {
        $this->degrees = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Degree>
     */
    public function getDegrees(): Collection
    {
        return $this->degrees;
    }

    public function addDegrees(Degree $degree): self
    {
        if (!$this->degrees->contains($degree)) {
            $this->degrees->add($degree);
            $degree->setUniversity($this);
        }

        return $this;
    }

    public function removeDegrees(Degree $degree): self
    {
        if ($this->degrees->removeElement($degree)) {
            // set the owning side to null (unless already changed)
            if ($degree->getUniversity() === $this) {
                $degree->setUniversity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addUniversity($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeUniversity($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

}
