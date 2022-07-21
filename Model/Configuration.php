<?php
/**
 * Copyright © Macopedia Sp. z o.o. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Macopedia\OmnibusDirective\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Configuration
{
    const HISTORICAL_PRICE_ACTIVE_PATH = 'omnibus_directive/historical_prices/active';
    const VALIDATE_REVIEW_ACTIVE_PATH = 'omnibus_directive/validate_review/active';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isHistoricalPriceActive(): bool
    {
        return $this->scopeConfig->isSetFlag(self::HISTORICAL_PRICE_ACTIVE_PATH);
    }

    public function isValidateReviewActive(): bool
    {
        return $this->scopeConfig->isSetFlag(self::VALIDATE_REVIEW_ACTIVE_PATH);
    }
}
