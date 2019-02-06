<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                "label" => "nom"
            ])
            ->add('description',TextareaType::class,[
                "label" => "description"
            ])
            ->add('city', TextType::class,[
                "label" => "ville"
            ])
            ->add('zip', TextType::class,[
                "label" => "code postal"
            ])
            ->add('price', NumberType::class,[
                "label" => "prix"
            ])
            ->add("articleCategory", EntityType::class,[
                    'multiple' => false,
                    'expanded' => false,
                    'class' => Category::class
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
