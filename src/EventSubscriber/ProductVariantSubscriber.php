<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Ikuzo\SyliusStockAlertPlugin\Entity\StockAlertInterface;
use Ikuzo\SyliusStockAlertPlugin\Message\SendStockAlert;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductVariantSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $em, private MessageBusInterface $bus, private ChannelContextInterface $channelContext)
    {
    }

    public function onProductVariantPreUpdate(GenericEvent $event)
    {
        if (!$this->channelContext->getChannel()->getStockAlertActive() || $this->channelContext->getChannel()->getStockAlertItemsBeforeSent() === 0) {
            return;
        }
        
        if ($event->getSubject() instanceof ProductVariantInterface) {
            $productVariant = $event->getSubject();
            $uow = $this->em->getUnitOfWork();
            $uow->computeChangeSets();
            $changeset = $uow->getEntityChangeSet($productVariant);
            
            if (in_array('onHand', array_keys($changeset)) 
            && $changeset['onHand'][1] >= $this->channelContext->getChannel()->getStockAlertItemsBeforeSent()) {
                $arrStockAlerts = $this->em->getRepository(StockAlertInterface::class)->findBy([
                    'channel' => $this->channelContext->getChannel(),
                    'productVariant' => $productVariant
                ]);
                foreach ($arrStockAlerts as $stockAlert) {
                    $this->bus->dispatch(new SendStockAlert($stockAlert->getId()));
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'sylius.product_variant.pre_update' => 'onProductVariantPreUpdate',
        ];
    }
}
