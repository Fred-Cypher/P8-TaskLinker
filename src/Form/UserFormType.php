<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('arrivalDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'entrée',
                'required' => true,
            ])
            ->add('contract', TextType::class, [
                'label' => 'Statut',
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Chef de projet' => 'ROLE_ADMIN',
                    'Collaborateur' => 'ROLE_USER',
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Rôle',
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // array -> string (affichage du formulaire)
                    return $rolesArray[0] ?? null;
                },
                function ($rolesString) {
                    // string -> array (stockage en BDD)
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }


}
