<?php

namespace AppBundle\Form;

use AppBundle\Entity\Field;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TypeType.
 */
class TypeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Libellé'])
            ->add('active', CheckboxType::class, ['label' => 'Actif', 'required' => false])
            ->add('fields', EntityType::class, [
                'class' => Field::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->where('f.active = TRUE')
                        ->orderBy('f.id', 'ASC');
                },
                'choice_label' => 'title',
                'label' => 'Champs supplémentaires',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
        ;
    }
}
