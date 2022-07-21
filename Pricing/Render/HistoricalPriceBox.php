<?php

namespace Macopedia\OmnibusDirective\Pricing\Render;

use Magento\Catalog\Pricing\Render\FinalPriceBox;

class HistoricalPriceBox extends FinalPriceBox
{
    public function canShowPrice(): float
    {
        $product = $this->getSaleableItem();

        return (int) $product->getData('historical_price') > 0;
    }
}
