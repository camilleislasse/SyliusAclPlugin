<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Form\Type;

use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

final class PermissionCollectionType extends AbstractType
{
    private const MISSING_PERMISSIONS = [
        0 => ['sylius_admin_catalog_promotion_product_variant_index', 'sylius_admin_product_variant_index'],
    ];

    public function __construct(
        private PrivilegeInterface $compositePrivilege,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $permissions = $this->compositePrivilege->getSupportedRouteMap([], true);

        foreach ($permissions as $resource => $actions) {
            $builder
                ->add($resource, ChoiceType::class, [
                    'label' => 'bitbag_sylius_acl_plugin.parent.' . $resource,
                    'multiple' => true,
                    'choices' => $this->getChoices($actions),
                    'required' => false,
                    'expanded' => true,
                ]);
        }

        $builder->addEventListener(FormEvents::SUBMIT, function (SubmitEvent $event) {
            $data = $event->getData();

            foreach ($data as $key => $subform) {
                // The CompositePrivilege class produces array, which misses one element on array_flip. The missing permission
                // sylius_admin_catalog_promotion_product_variant_index can be treated as the sylius_admin_product_variant_index,
                // so we select this on submitting form.
                foreach (self::MISSING_PERMISSIONS as $missingPermission) {
                    if (in_array($missingPermission[1], $subform, true)) {
                        $data[$key][] = $missingPermission[0];
                    } else {
                        if (($subkey = array_search($missingPermission[0], $subform, true)) !== false) {
                            unset($data[$key][$subkey]);
                        }
                        $data[$key] = array_values($data[$key]);
                    }
                }
            }

            $event->setData($data);
        });
    }

    private function getChoices(array $actions): array
    {
        $choices = [];
        foreach ($actions as $resource => $action) {
            $choices += [$resource => 'bitbag_sylius_acl_plugin.' . $action];
        }

        return array_flip($choices);
    }
}
