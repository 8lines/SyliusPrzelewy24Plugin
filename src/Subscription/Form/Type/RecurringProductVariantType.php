<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Form\Type;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringProductVariant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

final class RecurringProductVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recurring', CheckboxType::class, [
                'label' => 'bitbag.sylius_przelewy24_plugin.ui.recurring',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag.sylius_przelewy24_plugin.recurring.not_blank'
                    ]),
                ],
            ])
            ->add('recurringTimes', NumberType::class, [
                'label' => 'bitbag.sylius_przelewy24_plugin.ui.recurring_times',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag.sylius_przelewy24_plugin.recurring_times.not_blank'
                    ]),
                    new Range([
                        'min' => 2,
                        'minMessage' => 'bitbag.sylius_przelewy24_plugin.recurring_times.min_range',
                    ]),
                ],
            ])
            ->add('recurringIntervalInDays', NumberType::class, [
                'label' => 'bitbag.sylius_przelewy24_plugin.ui.recurring_interval_in_days',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag.sylius_przelewy24_plugin.recurring_interval_in_days.not_blank'
                    ]),
                    new Range([
                        'min' => 1,
                        'minMessage' => 'bitbag.sylius_przelewy24_plugin.recurring_interval_in_days.min_range',
                    ]),
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): bool
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => RecurringProductVariant::class,
        ]);

        return true;
    }
}
