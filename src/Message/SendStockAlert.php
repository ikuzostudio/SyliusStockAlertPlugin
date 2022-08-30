<?php

namespace Ikuzo\SyliusStockAlertPlugin\Message;

class SendStockAlert
{
    private $email;
    private $productVariantId;
    private $channelId;

    public function __construct(string $email, int $productVariantId, int $channelId)
    {
        $this->email = $email;
        $this->productVariantId = $productVariantId;
        $this->channelId = $channelId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getProductVariantId(): int
    {
        return $this->productVariantId;
    }

    public function getChannelId(): int
    {
        return $this->channelId;
    }
}
