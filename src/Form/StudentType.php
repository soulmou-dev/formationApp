<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', null, [
                'required' => true
            ])
            ->add('firstname', null, [
                'required' => true
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('classroom', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => 'name',
                'required' => true,
                'invalid_message' => 'La classe sélectionnée est invalide.',
            ])
            ->add('email', EmailType::class,[
                'mapped' => false,
                'label' => 'Email',
                'constraints' => [
                    new Assert\NotBlank(
                        [
                            'message' => 'L\'email est obligatoire'
                    ]),
                    new Assert\Email(
                        [
                            'message' => 'Le format de l\'email est invalide'
                    ]),
                    
                ]
            ])
            ->add('save', SubmitType::class,[
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
