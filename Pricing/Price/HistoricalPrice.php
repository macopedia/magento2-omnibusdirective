<?php
/**
 * Copyright © Macopedia Sp. z o.o. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Macopedia\OmnibusDirective\Pricing\Price;

use Magento\Framework\Pricing\Price\AbstractPrice;

class HistoricalPrice extends AbstractPrice
{
    /**
     * Price type final
     */
    const PRICE_CODE = 'historical_price';

    /**
     * Retrieve historical price.
     *
     * @return float
     */
    public function getValue(): float
    {
        return (float) $this->product->getData('historical_price');
    }
}
