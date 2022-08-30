<?php

namespace Ikuzo\SyliusStockAlertPlugin\Twig;

use Ikuzo\SyliusStockAlertPlugin\Repository\StockAlertRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StockAlertExtension extends AbstractExtension
{
    public function __construct(private StockAlertRepository $stockAlertRepository)
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_stock_alerts', [$this, 'getStockAlerts']),
        ];
    }

    public function getStockAlerts(ProductVariantInterface $productVariant, ChannelInterface $channel): array
    {
        return $this->stockAlertRepository->findByProductVariant($productVariant, $channel);
    }
}