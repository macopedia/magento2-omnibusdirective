<?php
/**
 * Copyright © Macopedia Sp. z o.o. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Macopedia\OmnibusDirective\Plugin\Review;

use Macopedia\OmnibusDirective\Model\Configuration;
use Macopedia\OmnibusDirective\Model\Review\OrderedProduct;
use Magento\Framework\App\RequestInterface;
use Magento\Review\Model\Review;

class AfterValidate
{
    /**
     * @var OrderedProduct
     */
    private $orderedProduct;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(
        OrderedProduct $orderedProduct,
        RequestInterface $request,
        Configuration $configuration
    ) {
        $this->orderedProduct = $orderedProduct;
        $this->request = $request;
        $this->configuration = $configuration;
    }

    public function afterValidate(Review $subject, $result)
    {
        if (!$this->configuration->isValidateReviewActive()) {
            return $result;
        }

        $orderId = $subject->getData('order_id');
        $productId = $this->request->getParam('id');
        if (empty($orderId) || !$this->orderedProduct->validateProductOrdered($productId, $orderId)) {
            $result = [];
            $result[] = (__('This product was not purchased with this order. Please check your order number and try again.'));
        }

        return $result;
    }
}
