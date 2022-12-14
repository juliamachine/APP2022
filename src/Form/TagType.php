<?php

/**
 * Tag Type.
 */

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

/**
 * TagType class.
 */
class TagType extends AbstractType
{
    /**
     * Building form of tag.
     *
     * @param FormBuilderInterface $builder form builder interface
     * @param array                $options options array
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => t('label.title'),
            ])
            ->add('tasks')
            ->add('notes')
        ;
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver resolver
     *
     * @return void void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
