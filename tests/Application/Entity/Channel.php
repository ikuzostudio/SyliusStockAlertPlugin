<?php

declare(strict_types=1);

namespace Tests\Ikuzo\SyliusStockAlertPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ikuzo\SyliusStockAlertPlugin\Model\StockAlertChannelInterface;
use Ikuzo\SyliusStockAlertPlugin\Model\StockAlertChannelTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;

/**
 * @ORM\Table(name="sylius_channel")
 * @ORM\Entity()
 */
class Channel extends BaseChannel implements StockAlertChannelInterface
{
    use StockAlertChannelTrait;
}