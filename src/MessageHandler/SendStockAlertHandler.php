<?php

namespace Ikuzo\SyliusStockAlertPlugin\MessageHandler;

use Doctrine\ORM\EntityManagerInterface;
use Ikuzo\SyliusStockAlertPlugin\Entity\StockAlertInterface;
use Ikuzo\SyliusStockAlertPlugin\Message\SendStockAlert;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendStockAlertHandler implements MessageHandlerInterface
{
    public function __construct(private EntityManagerInterface $em, private SenderInterface $sender)
    {    
    }

    public function __invoke(SendStockAlert $message)
    {
        $stockAlert = $this->em->getRepository(StockAlertInterface::class)->find($message->getStockAlertId());

        if (!$stockAlert instanceof StockAlertInterface) {
            throw new \Exception("StockAlert #{$message->getStockAlertId()} not found", 1);
        }

        $productVariant = $stockAlert->getProductVariant();
        $productVariant->setCurrentLocale($stockAlert->getLocale());

        $product = $productVariant->getProduct();
        $product->setCurrentLocale($stockAlert->getLocale());

        $options = [
            'productVariant' => $productVariant,
            'product' => $product,
            'channel' => $stockAlert->getChannel(),
            'email' => $stockAlert->getEmail(),
            'localeCode' => $stockAlert->getLocale()
        ];

        $this->sender->send('ikuzo_stock_alert', [$stockAlert->getEmail()], $options);

        $this->em->remove($stockAlert);
        $this->em->flush();
    }
}
