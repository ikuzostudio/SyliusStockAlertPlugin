<?php

namespace Ikuzo\SyliusStockAlertPlugin\MessageHandler;

use Doctrine\ORM\EntityManagerInterface;
use Ikuzo\SyliusStockAlertPlugin\Message\SendStockAlert;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendStockAlertHandler implements MessageHandlerInterface
{
    public function __construct(private EntityManagerInterface $em, private SenderInterface $sender)
    {    
    }

    public function __invoke(SendStockAlert $message)
    {
        $channel = $this->em->getRepository(ChannelInterface::class)->find($message->getChannelId());
        if (!$channel instanceof ChannelInterface) {
            return false;
        }

        $productVariant = $this->em->getRepository(ProductVariantInterface::class)->find($message->getProductVariantId());
        if (!$productVariant instanceof ProductVariantInterface) {
            return false;
        }

        $email = $message->getEmail();

        $recipients = [
            $email
        ];

        $options = [
            'productVariant' => $productVariant,
            'channel' => $channel,
            'email' => $email
        ];

        $this->sender->send('stock_alert', $recipients, $options);
    }
}
