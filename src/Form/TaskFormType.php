<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class ,[ 'label' => 'Titre'])
            ->add('priority', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Mineur' => 0,
                    'Normal' => 1,
                    'Majeur' => 2
                ]
            ])
            ->add('deadline', DateType::class, [
                'label' => 'Date limite (jour/mois/année)',
                'format' => 'dd-MM-yyy'
            ])
            ->add('status', EntityType::class, [
                'label' => 'Status',
                'class' => TaskStatus::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
