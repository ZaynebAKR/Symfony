<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'required' => true,
        ]) 
            ->add('contact')
            ->add('categoriep', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Sport' => 'Sport',
                    'Art' => 'Art',
                    'Education' => 'Education',
                    'Loisir' => 'Loisir',
                    'Comunauté' => 'Comunauté',
                    'Environnement' => 'Environnement',
                    'Education' => 'Education',
                ],
                'placeholder' => 'Choisir une catégorie',  ])
            ->add('localisation')
            ->add('description', TextareaType::class)

            ->add('ouverture', DateTimeType::class, [
                'label' => 'Ouverture',
                'required' => false, // Make the field not required
                'html5' => true,
                'widget' => 'single_text',
                'empty_data' => null, // Allow null value
                'attr' => ['min' => (new \DateTime())->format('Y-m-d\TH:i')],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false, // This prevents the field from being mapped to the entity
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'onchange' => 'document.getElementById("image-file-name").textContent = this.files[0].name;',
                ],
                ])
            ->add('logo', FileType::class, [
                'label' => 'Logo',
                'mapped' => false, // This prevents the field from being mapped to the entity
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'onchange' => 'document.getElementById("logo-file-name").textContent = this.files[0].name;',
                ],
            ])
     
            
           
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
