<?php

namespace Ikuzo\SyliusStockAlertPlugin\Form\Extension;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

final class ChannelTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stockAlertActive', CheckboxType::class, [
                'required' => true,
                'label' => 'ikuzo_stock_alert.form.active',
            ])
            ->add('stockAlertItemsBeforeSent', NumberType::class, [
                'required' => true,
                'label' => 'ikuzo_stock_alert.form.items_before_sent',
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ChannelType::class];
    }
}