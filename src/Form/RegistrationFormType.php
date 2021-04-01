<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Valid;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'constraints' => [
                    new NotNull(),
                    new Length(null, 2, 12),
                ],
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotNull(),
                    new LessThanOrEqual('-14 years', null, 'You should be older than 14 years to register.'),
                ],
            ])
            ->add('facebookUrl', UrlType::class, [
                'constraints' => [
                    new NotNull(),
                    new Url(),
                ],
            ])
            ->add('twitterUrl', UrlType::class, [
                'constraints' => [
                    new NotNull(),
                    new Url(),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotNull(),
                    new Email(),
                ],
            ])
            ->add('country', null, [
                'constraints' => [
                    new Valid(),
                    new NotNull(),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('captcha', ReCaptchaType::class, [
                'mapped' => false,
                'type' => 'invisible' // (invisible, checkbox)
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => true,
            ]
        ]);
    }
}
