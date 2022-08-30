<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\Model;

use Doctrine\ORM\Mapping as ORM;

trait StockAlertChannelTrait {

    /**
     * @ORM\Column(name="stock_alert_active", type="boolean")
     **/
    protected $stockAlertActive = false;
    
    /**
     * @ORM\Column(name="stock_alert_items_before_sent", type="integer", nullable="false", options={"default" : 1})
     **/
    protected $stockAlertItemsBeforeSent = 1;

    public function getStockAlertActive(): bool
    {
        return $this->stockAlertActive;
    }

    public function setStockAlertActive(bool $stockAlertActive): void
    {
        $this->stockAlertActive = $stockAlertActive;
    }

    public function getStockAlertItemsBeforeSent(): int
    {
        return (int)$this->stockAlertItemsBeforeSent;
    }

    public function setStockAlertItemsBeforeSent(int $stockAlertItemsBeforeSent): void
    {
        $this->stockAlertItemsBeforeSent = $stockAlertItemsBeforeSent;
    }
}