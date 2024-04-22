<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('description', TextareaType::class)
                ->add('image', FileType::class, [
                    'label' => 'Image', // Set label for the file input
                    'required' => true, // Not required, since image upload is optional
                    'mapped' => false, // This field is not mapped to any property of your entity
                    'attr' => [
                        'accept' => 'image/*',
                        // Use JavaScript to display the selected file name in a separate label
                        'onchange' => 'document.getElementById("image-file-name").textContent = this.files[0].name;',
                    ],])
                
->add('nomp', TextType::class, [
    'label' => 'Nomp',
    'required' => true,
])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
