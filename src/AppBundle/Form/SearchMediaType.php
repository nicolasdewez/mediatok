<?php

namespace AppBundle\Form;

use AppBundle\Entity\Format;
use AppBundle\Entity\Type;
use AppBundle\Model\SearchMedia;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Class SearchMediaType.
 */
class SearchMediaType extends AbstractType
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('protocol', ChoiceType::class, [
                'choices' => ['SFTP' => SearchMedia::PROTOCOL_SFTP],
                'label' => 'Protocole',
            ])
            ->add('host', TextType::class, ['label' => 'Serveur distant'])
            ->add('port', NumberType::class, ['label' => 'Port'])
            ->add('username', TextType::class, ['label' => 'Nom d\'utilisateur'])
            ->add('password', TextType::class, ['label' => 'Mot de passe'])
            ->add('directory', TextType::class, ['label' => 'Répertoire'])
            ->add('recursive', CheckboxType::class, ['label' => 'Récursivité', 'required' => false])
            ->add('filter', TextType::class, ['label' => 'Filtre'])
            ->add('fileMode', ChoiceType::class, [
                'choices' => [
                    'Les fichiers' => SearchMedia::FILE_MODE_FILES,
                    'Les dossiers' => SearchMedia::FILE_MODE_DIRECTORIES,
                ],
                'label' => 'Enregistrer',
            ])
            ->add('saveMode', ChoiceType::class, [
                'choices' => [
                    'Ne rien faire' => SearchMedia::SAVE_MODE_NOTHING,
                    'Mettre à jour' => SearchMedia::SAVE_MODE_UPDATE,
                ],
                'label' => 'Que faire si l\'élément existe ?',
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.active = TRUE')
                        ->orderBy('t.title', 'ASC');
                },
                'choice_label' => 'title',
                'label' => 'Type',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $type = $event->getData()->getType();

                // Change form fields
                $this->addFormatField($form, $type);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();

                $idType = $event->getData()['type'];
                $type = $this->manager->getRepository(Type::class)->find($idType);
                if (null === $type) {
                    throw new \InvalidArgumentException(sprintf(
                        'Type %d not found in form %s',
                        $idType,
                        $this->getBlockPrefix()
                    ));
                }

                // Change form fields
                $this->addFormatField($form, $type);
            })
        ;
    }

    /**
     * @param FormInterface $form
     * @param Type|null     $type
     */
    private function addFormatField(FormInterface $form, Type $type = null)
    {
        $form->add('format', EntityType::class, [
            'class' => Format::class,
            'query_builder' => function (EntityRepository $er) use ($type) {
                return $er->createQueryBuilder('f')
                    ->where('f.active = TRUE')
                    ->andWhere('f.type = :type')
                    ->orderBy('f.title', 'ASC')
                    ->setParameter('type', $type)
                    ;
            },
            'choice_label' => 'title',
            'label' => 'Format',
        ]);
    }
}
