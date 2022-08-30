<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuBuilder
{
    public function addItems(MenuBuilderEvent $event): void
    {
        /** @var ItemInterface $menu */
        $menu = $event->getMenu()->getChild('catalog');

        $menu
            ->addChild('ikuzo_stock_alert', ['route' => 'ikuzo_stock_alert_admin_stock_alert_index'])
            ->setLabel('ikuzo_stock_alert.menu.stock_alert.label')
            ->setLabelAttribute('icon', 'warning')
        ;
    }
}
