<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Form\Extension;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Form\Type\RecurringProductVariantType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType as ProductVariantFormType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class RecurringPrzelewy24ProductVariantExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recurringPrzelewy24ProductVariant', RecurringProductVariantType::class)
        ;
    }

    public static function getExtendedTypes(): array
    {
        return [ProductVariantFormType::class];
    }
}
