<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContactType
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'label' => 'contactForm.name',
            ])
            ->add('email', EmailType::class, [
                'empty_data' => '',
                'label' => 'contactForm.email',
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => '',
                'label' => 'contactForm.message',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'contactForm.submit',
            ])
            ->add('service', ChoiceType::class, [
                'label' => 'contactForm.service',
                'choices' => [
                    'compta' => 'compta@demo.com',
                    'support' => 'support@demo.com',
                    'marketing' => 'marketing@demo.com'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
