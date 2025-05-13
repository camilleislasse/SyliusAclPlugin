<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Form\Type;

use BitBag\SyliusAclPlugin\Form\Type\Translation\RoleTranslationType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class RoleType extends AbstractResourceType
{
    private DataTransformerInterface $dataTransformer;

    public function __construct(
        string $dataClass,
        DataTransformerInterface $dataTransformer,
        $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->dataTransformer = $dataTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => RoleTranslationType::class,
            ])
            ->add('permissions', PermissionCollectionType::class, [
                'required' => false,
            ]);

        $builder->get('permissions')->addModelTransformer($this->dataTransformer);
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_acl_plugin_role';
    }
}
