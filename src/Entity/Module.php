<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Traits\TimestampableTrait;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['name',], message: 'Cet module existe déja.')]
class Module
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'module')]
    private Collection $courses;

    /**
     * @var Collection<int, Teacher>
     */
    #[ORM\OneToMany(targetEntity: Teacher::class, mappedBy: 'module')]
    private Collection $teachers;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\ManyToMany(targetEntity: Classroom::class, mappedBy: 'modules')]
    private Collection $classrooms;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->classrooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setModule($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getModule() === $this) {
                $course->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Teacher>
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(Teacher $teacher): static
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers->add($teacher);
            $teacher->setModule($this);
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): static
    {
        if ($this->teachers->removeElement($teacher)) {
            // set the owning side to null (unless already changed)
            if ($teacher->getModule() === $this) {
                $teacher->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Classroom>
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    public function addClassroom(Classroom $classroom): static
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms->add($classroom);
            $classroom->addModule($this);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): static
    {
        if ($this->classrooms->removeElement($classroom)) {
            $classroom->removeModule($this);
        }

        return $this;
    }
}
