<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\Model;

interface StockAlertChannelInterface {
    public function getStockAlertActive(): bool;
    public function setStockAlertActive(bool $input): void;
    public function getStockAlertItemsBeforeSent(): int;
    public function setStockAlertItemsBeforeSent(int $input): void;
}
