<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\Command;

use Ikuzo\SyliusStockAlertPlugin\Message\SendStockAlert;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ikuzo\SyliusStockAlertPlugin\Model\StockAlertChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Messenger\MessageBusInterface;


class StockAlertProcessCommand extends Command
{
    protected static $defaultName = 'ikuzo:stock-alert:process';

    protected $avisVerifiesOrdersProcessor;

    public function __construct(private RepositoryInterface $stockAlertRepository, private MessageBusInterface $bus) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->stockAlertRepository->findAll() as $stockAlert) {
            $channel = $stockAlert->getChannel();
            $productVariant = $stockAlert->getProductVariant();

            if (
                $channel instanceof StockAlertChannelInterface 
                && $channel->getStockAlertActive()
                && $productVariant instanceof ProductVariantInterface
                && $productVariant->getOnHand() >= $channel->getStockAlertItemsBeforeSent()
            ) {
                $this->bus->dispatch(new SendStockAlert($stockAlert->getId()));
            }
        }

        return Command::SUCCESS;
    }
}