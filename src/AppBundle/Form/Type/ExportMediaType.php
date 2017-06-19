<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Type;
use AppBundle\Model\ExportMedia;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ExportType.
 */
class ExportMediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    'Fichier CSV' => ExportMedia::MODE_CSV,
                    'Fichier PDF' => ExportMedia::MODE_PDF,
                ],
                'label' => 'Format d\'enregistrement',
            ])
            ->add('filter', TextType::class, ['label' => 'Filtre'])
            ->add('types', EntityType::class, [
                'class' => Type::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.active = TRUE')
                        ->orderBy('t.title', 'ASC');
                },
                'choice_label' => 'title',
                'multiple' => true,
                'label' => 'Types',
            ])
        ;
    }
}
