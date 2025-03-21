<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'contact_form.name_placeholder',
                    'class' => 'input input-bordered w-full',
                    'data-trans-placeholder' => 'contact_form.name_placeholder'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'contact_form.email_placeholder',
                    'class' => 'input input-bordered w-full',
                    'data-trans-placeholder' => 'contact_form.email_placeholder'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'contact_form.message_placeholder',
                    'rows' => 3,
                    'class' => 'textarea textarea-bordered w-full',
                    'data-trans-placeholder' => 'contact_form.message_placeholder'
                ]
            ])
            ->add('consent', CheckboxType::class, [
                'required' => true,
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'class' => 'checkbox checkbox-sm',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
