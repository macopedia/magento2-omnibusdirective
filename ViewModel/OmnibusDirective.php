<?php
/**
 * Copyright © Macopedia Sp. z o.o. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Macopedia\OmnibusDirective\ViewModel;

use Macopedia\OmnibusDirective\Model\Configuration;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class OmnibusDirective implements ArgumentInterface
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(
        Configuration $configuration
    ) {
        $this->configuration = $configuration;
    }

    public function isHistoricalPriceActive(): bool
    {
        return $this->configuration->isHistoricalPriceActive();
    }

    public function isValidateReviewActive(): bool
    {
        return $this->configuration->isValidateReviewActive();
    }
}
