<?php
/**
 * Copyright © Macopedia Sp. z o.o. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Macopedia\OmnibusDirective\Observer;

use Macopedia\OmnibusDirective\Model\Configuration;
use Macopedia\OmnibusDirective\Model\Product\HistoricalPrice;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class UpdateHistoricalPriceAdminhtmlObserver implements ObserverInterface
{
    /**
     * @var HistoricalPrice
     */
    private $historicalPrice;
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(
        HistoricalPrice $historicalPrice,
        Configuration $configuration
    ) {
        $this->historicalPrice = $historicalPrice;
        $this->configuration = $configuration;
    }

    public function execute(Observer $observer)
    {
        if ($this->configuration->isHistoricalPriceActive()) {
            /** @var Product $product */
            $product = $observer->getEvent()->getData('product');
            $this->historicalPrice->updateHistoricalPrice($product, $product->getOrigData(), $product->getData());
        }
    }
}
