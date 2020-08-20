<?php

namespace App\Form;

use App\Entity\Worksheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class EditWorksheetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate')
            ->add('endDate')
            ->add('pause')
            ->add('overtime')
            ->add('activities', TextType::class)
            ->add('materials', TextType::class)
            ->add('hourRate')
            ->add('transportCost')
            // ->add('time', IntegerType::class)
            ->add('period')
            ->add('employee')
            ->add('Opslaan', SubmitType::class)
        ;

        $builder->get('activities')
            ->addModelTransformer(new CallbackTransformer(
                function ($activitiesAsArray) {
                    // transform the array to a string
                    return implode(', ', $activitiesAsArray);
                },
                function ($activitiesAsString) {
                    // transform the string back to an array
                    return explode(', ', $activitiesAsString);
                }
            ))
        ;
        $builder->get('materials')
            ->addModelTransformer(new CallbackTransformer(
                function ($materialsAsArray) {
                    // transform the array to a string
                    return implode(', ', $materialsAsArray);
                },
                function ($materialsAsString) {
                    // transform the string back to an array
                    return explode(', ', $materialsAsString);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Worksheet::class,
            'allow_extra_fields' => true,
        ]);
    }
}
