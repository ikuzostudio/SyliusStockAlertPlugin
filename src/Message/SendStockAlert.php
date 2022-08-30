<?php

namespace Ikuzo\SyliusStockAlertPlugin\Message;

class SendStockAlert
{
    public function __construct(private int $stockAlertId)
    {
    }

    public function getStockAlertId(): int
    {
        return $this->stockAlertId;
    }
}
