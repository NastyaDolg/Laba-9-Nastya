<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddNewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
    $builder
        ->add('name', TextType::class, [

            'required' => true,
            'constraints' => [new Length([
                'min' => 3,
                
            ]),
                new NotBlank([
                    'message' => 'Пожалуйста введите название новости',
                ]),

        ],
        ])
        ->add('description', TextareaType::class, [

            'required' => true,
            'constraints' => [new Length([
                'min' => 3,

            ]),
                new NotBlank([
                    'message' => 'Пожалуйста введите пароль',
                ]),

            ],
        ])
        ->add('content', TextareaType::class, [

            'required' => true,
            'constraints' => [new Length([
                'min' => 3,

            ]),
                new NotBlank([
                    'message' => 'Пожалуйста введите пароль',
                ]),

            ],
        ])
        ->add('fotopath', FileType::class, [
                'label' => 'Brochure (PDF file)',
                // неотображенное означает, что это поле не ассоциировано ни с одним свойством сущности
                'mapped' => false,

                // сделайте его необязательным, чтобы вам не нужно было повторно загружать PDF-файл
                // каждый раз, когда будете редактировать детали Product
                'required' => false,

                // неотображенные полля не могут определять свою валидацию используя аннотации
                // в ассоциированной сущности, поэтому вы можете использовать органичительные классы PHP
                'constraints' => [
                    new File([
                        'maxSize' => '6144k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Пожалуйста загрузите фото с расширениями img/png/svg',
                    ]),
                    new NotBlank([
                        'message' => 'Пожалуйста загрузите фото',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
