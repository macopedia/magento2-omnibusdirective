<?php
/**
 * Copyright © Macopedia Sp. z o.o. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Macopedia\OmnibusDirective\Model\Review;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderedProduct
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param string $productId
     * @param string $orderId
     * @return false
     */
    public function validateProductOrdered(string $productId, string $orderId): bool
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $orderId, 'eq')
            ->create();

        $orderList = $this->orderRepository->getList($searchCriteria)->getItems();

        foreach ($orderList as $order) {
            foreach ($order->getItems() as $item) {
                if ($item->getProductId() == $productId) {
                    return true;
                }
            }
        }

        return false;
    }
}
