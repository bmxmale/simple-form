<?php

namespace App\Form;

use App\Entity\SimpleFormInput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class SimpleFormInputType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Imię',
                'constraints' => [
                    new NotBlank(['message' => 'Proszę podać imię']),
                    new Length(['min' => 1, 'max' => 60, 'maxMessage' => 'Imię może zawierać maksymalnie 60 znaków'])
                ],
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5',
                    'placeholder' => 'Imię'
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nazwisko',
                'constraints' => [
                    new NotBlank(['message' => 'Proszę podać nazwisko']),
                    new Length(['min' => 1, 'max' => 100, 'maxMessage' => 'Nazwisko może zawierać maksymalnie 100 znaków'])
                ],
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5',
                    'placeholder' => 'Nazwisko'
                ],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
            ])
            ->add('attachment', FileType::class, [
                'label' => 'Załącznik',
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Proszę załączyć plik']),
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/heic',
                            'image/heif'
                        ],
                        'mimeTypesMessage' => 'Proszę przesłać prawidłowy obrazek (JPEG, PNG, GIF, HEIC, HEIF )',
                    ])
                ],
                'attr' => ['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'],
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
                'help' => 'Załącz plik w formacie JPEG, PNG, GIF, HEIC lub HEIF',
                'help_attr' => ['class' => 'mt-2 text-sm text-gray-500']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Wyślij',
                'attr' => ['class' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SimpleFormInput::class,
            'csrf_protection' => false,
        ]);
    }
}
