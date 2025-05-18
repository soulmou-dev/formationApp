<?php
namespace App\Event;

use App\Entity\Student;
use Symfony\Contracts\EventDispatcher\Event;

class StudentRegisteredEvent extends Event
{
    public const NAME = 'student.registered';

    public function __construct(
        private Student $student,
        private string $plainPassword
    ) {}

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
