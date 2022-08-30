<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;

class StockAlertRepository extends EntityRepository implements StockAlertRepositoryInterface
{
    public function findByGroupByProductVariant()
    {
        $qb = $this->createQueryBuilder('sa');

        return $qb
            ->groupBy('sa.productVariant')
        ;
    }

    public function findByProductVariant(ProductVariantInterface $productVariant, ChannelInterface $channel): array
    {
        $qb = $this->createQueryBuilder('sa');

        return $qb
            ->where('sa.productVariant = :productVariant')
            ->andWhere('sa.channel = :channel')
            ->setParameter('productVariant', $productVariant)
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getResult()
        ;
    }
}
