<?php

namespace App\Entity;

use App\Repository\DegreeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DegreeRepository::class)]
class Degree
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'degree')]
    #[ORM\JoinColumn(nullable: false)]
    private ?University $university = null;

    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'degrees')]
    private Collection $subject;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'degrees')]
    private Collection $users;

    #[ORM\Column(length: 255)]
    private ?string $school = null;

    #[ORM\OneToMany(mappedBy: 'degree', targetEntity: Course::class)]
    private Collection $courses;

    public function __construct()
    {
        $this->subject = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->courses = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUniversity(): ?University
    {
        return $this->university;
    }

    public function setUniversity(?University $university): self
    {
        $this->university = $university;

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubject(): Collection
    {
        return $this->subject;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subject->contains($subject)) {
            $this->subject->add($subject);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        $this->subject->removeElement($subject);

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
            $user->addDegree($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeDegree($this);
        }

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(string $school): self
    {
        $this->school = $school;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setDegree($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getDegree() === $this) {
                $course->setDegree(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
