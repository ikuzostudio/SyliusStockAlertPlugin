<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\EligibilityChecker;

use Ikuzo\SyliusStockAlertPlugin\Entity\StockAlertInterface;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Webmozart\Assert\Assert;

final class CompositeStockAlertEligibilityChecker implements StockAlertEligibilityCheckerInterface
{
    /** @var StockAlertEligibilityCheckerInterface[] */
    private $stockAlertEligibilityCheckers;

    /**
     * @param StockAlertEligibilityCheckerInterface[] $stockAlertEligibilityCheckers
     */
    public function __construct(RewindableGenerator $stockAlertEligibilityCheckers)
    {
        Assert::notEmpty($stockAlertEligibilityCheckers);
        Assert::allIsInstanceOf($stockAlertEligibilityCheckers, StockAlertEligibilityCheckerInterface::class);

        $this->stockAlertEligibilityCheckers = $stockAlertEligibilityCheckers;
    }

    public function isEligible(StockAlertInterface $stockAlert): bool
    {
        foreach ($this->stockAlertEligibilityCheckers as $stockAlertEligibilityChecker) {
            if (!$stockAlertEligibilityChecker->isEligible($stockAlert)) {
                return false;
            }
        }

        return true;
    }
}
