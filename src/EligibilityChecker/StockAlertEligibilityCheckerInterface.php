<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\EligibilityChecker;

use Ikuzo\SyliusStockAlertPlugin\Entity\StockAlertInterface;

interface StockAlertEligibilityCheckerInterface
{
    public function isEligible(StockAlertInterface $StockAlert): bool;
}
