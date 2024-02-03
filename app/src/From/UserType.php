<?php

namespace App\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class UserType extends AbstractType
{

    const NAME = 'UserRegistrationForm';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['constraints' => [new NotBlank(), new Length(['min' => 5])]])
            ->add('surname', TextType::class, ['data' => 'Ivanov'])
            ->add('email', EmailType::class, ['data' => 'i.ivanov@example.ru'])
            ->add('submit', SubmitType::class, ['label' => 'Save']);
    }
}