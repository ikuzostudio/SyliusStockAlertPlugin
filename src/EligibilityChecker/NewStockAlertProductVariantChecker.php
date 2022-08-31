<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\EligibilityChecker;

use Ikuzo\SyliusStockAlertPlugin\Entity\StockAlertInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class NewStockAlertProductVariantChecker implements StockAlertEligibilityCheckerInterface
{
    public function isEligible(StockAlertInterface $stockAlert): bool
    {
        $productVariant = $stockAlert->getProductVariant();
        
        return
            $productVariant instanceof ProductVariantInterface
            && $productVariant->getOnHand() === 0
        ;
    }
}
