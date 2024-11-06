<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Form\Extension;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Form\Type\Przelewy24ProductVariantType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType as ProductVariantFormType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class RecurringProductVariantExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('przelewy24ProductVariant', Przelewy24ProductVariantType::class)
        ;
    }

    public static function getExtendedTypes(): array
    {
        return [ProductVariantFormType::class];
    }
}
