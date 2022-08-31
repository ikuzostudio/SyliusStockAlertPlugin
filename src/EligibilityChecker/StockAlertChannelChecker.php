<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\EligibilityChecker;

use Ikuzo\SyliusStockAlertPlugin\Model\StockAlertChannelInterface;
use Ikuzo\SyliusStockAlertPlugin\Entity\StockAlertInterface;

final class StockAlertChannelChecker implements StockAlertEligibilityCheckerInterface
{
    public function isEligible(StockAlertInterface $stockAlert): bool
    {
        $channel = $stockAlert->getChannel();

        return
            $channel instanceof StockAlertChannelInterface
            && $channel->getStockAlertActive() === true 
            && $channel->getStockAlertActive() !== null 
            && $channel->getStockAlertItemsBeforeSent() > 0
            && $channel->getStockAlertItemsBeforeSent() !== null
        ;
    }
}
