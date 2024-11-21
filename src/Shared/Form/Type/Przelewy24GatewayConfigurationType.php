<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Form\Type;

use Przelewy24\Enums\Environment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class Przelewy24GatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('environment', ChoiceType::class, [
                'label' => 'bitbag.sylius_przelewy24_plugin.ui.environment',
                'choices' => [
                    'bitbag.sylius_przelewy24_plugin.ui.sandbox' => Environment::SANDBOX,
                    'bitbag.sylius_przelewy24_plugin.ui.production' => Environment::PRODUCTION,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag.sylius_przelewy24_plugin.environment.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('merchant_id', TextType::class, [
                'label' => 'bitbag.sylius_przelewy24_plugin.ui.merchant_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag.sylius_przelewy24_plugin.merchant_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('reports_key', TextType::class, [
                'label' => 'bitbag.sylius_przelewy24_plugin.ui.reports_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag.sylius_przelewy24_plugin.reports_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('crc_key', TextType::class, [
                'label' => 'bitbag.sylius_przelewy24_plugin.ui.crc_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag.sylius_przelewy24_plugin.crc_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
        ;
    }
}
