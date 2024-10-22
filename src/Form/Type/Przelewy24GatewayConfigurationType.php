<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class Przelewy24GatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('crc', TextType::class, [
                'label' => 'bitbag_sylius_przelewy24_plugin.ui.crc',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_przelewy24_plugin.crc.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('reports_key', TextType::class, [
                'label' => 'bitbag_sylius_przelewy24_plugin.ui.reports_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_przelewy24_plugin.reports_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('merchant_id', TextType::class, [
                'label' => 'bitbag_sylius_przelewy24_plugin.ui.merchant_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_przelewy24_plugin.merchant_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('live', CheckboxType::class, [
                'label' => 'bitbag_sylius_przelewy24_plugin.ui.live',
            ])
        ;
    }
}
