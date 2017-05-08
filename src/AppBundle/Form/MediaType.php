<?php

namespace AppBundle\Form;

use AppBundle\Entity\Field;
use AppBundle\Entity\Format;
use AppBundle\Entity\Media;
use AppBundle\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Class MediaType.
 */
class MediaType extends AbstractType
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
            ->add('title', TextType::class, ['label' => 'Libellé'])
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
                if (null !== $type) {
                    $this->addAdditionalFieldsWithValues($form, $event->getData());
                }
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
                $this->addAdditionalFields($form, $type);

                // Set attribute fields in object
                $this->setFieldsIntoMedia($event->getForm()->getData(), $event->getData());
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

    /**
     * @param FormInterface $form
     * @param Type|null     $type
     */
    private function addAdditionalFields(FormInterface $form, Type $type = null)
    {
        $fields = $this->manager->getRepository(Field::class)->getActiveAndSortedByType($type);
        foreach ($fields as $field) {
            $form->add(sprintf('field_%d', $field->getId()), TextType::class, [
                'label' => $field->getTitle(),
                'required' => false,
                'mapped' => false,
            ]);
        }
    }

    /**
     * @param FormInterface $form
     * @param Media         $media
     */
    private function addAdditionalFieldsWithValues(FormInterface $form, Media $media)
    {
        if (null === $media->getType()) {
            return;
        }

        $fields = $this->manager->getRepository(Field::class)->getActiveAndSortedByType($media->getType());
        foreach ($fields as $field) {
            $data = '';
            if (isset($media->getFields()[$field->getId()])) {
                $data = $media->getFields()[$field->getId()];
            }

            $form->add(sprintf('field_%d', $field->getId()), TextType::class, [
                'label' => $field->getTitle(),
                'required' => false,
                'mapped' => false,
                'data' => $data,
            ]);
        }
    }

    /**
     * @param Media $media
     * @param array $data
     */
    private function setFieldsIntoMedia(Media $media, array $data)
    {
        $keys = array_filter(array_keys($data), function ($key) {
            return preg_match('/^field_/', $key);
        });

        $fields = [];
        foreach ($keys as $key) {
            $id = substr($key, 6);  // Length of "field_"
            $fields[$id] = $data[$key];
        }

        $media->setFields($fields);
    }
}
