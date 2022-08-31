<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\EligibilityChecker;

use Ikuzo\SyliusStockAlertPlugin\Model\StockAlertChannelInterface;
use Ikuzo\SyliusStockAlertPlugin\Entity\StockAlertInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class StockAlertProductVariantChecker implements StockAlertEligibilityCheckerInterface
{
    public function isEligible(StockAlertInterface $stockAlert): bool
    {
        $channel = $stockAlert->getChannel();
        $productVariant = $stockAlert->getProductVariant();
        
        return
            $channel instanceof StockAlertChannelInterface
            && $productVariant instanceof ProductVariantInterface
            && $productVariant->getOnHand() >= $channel->getStockAlertItemsBeforeSent()
        ;
    }
}
