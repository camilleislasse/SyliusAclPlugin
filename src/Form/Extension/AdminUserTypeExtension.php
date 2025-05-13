<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Form\Extension;

use Sylius\Bundle\CoreBundle\Form\Type\User\AdminUserType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

final class AdminUserTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private string $roleClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rolesResources', EntityType::class, [
                'label' => 'bitbag_sylius_acl_plugin.ui.roles',
                'class' => $this->roleClass,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            ])
            ->add('enablePermissionChecker', CheckboxType::class, [
                'label' => 'bitbag_sylius_acl_plugin.ui.enabled_permission_checker',
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            AdminUserType::class,
        ];
    }
}
