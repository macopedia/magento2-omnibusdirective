<?php
/**
 * Copyright © Macopedia Sp. z o.o. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Macopedia\OmnibusDirective\Cron;

use Macopedia\OmnibusDirective\Model\Configuration;
use Macopedia\OmnibusDirective\Model\Product\HistoricalPrice;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\StateException;
use Psr\Log\LoggerInterface;

class CleanupHistoricalPrices
{
    protected $logger;
    /**
     * @var HistoricalPrice
     */
    private $historicalPrice;
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @param LoggerInterface $logger
     * @param HistoricalPrice $historicalPrice
     * @param Configuration $configuration
     */
    public function __construct(
        LoggerInterface $logger,
        HistoricalPrice $historicalPrice,
        Configuration $configuration
    ) {
        $this->logger = $logger;
        $this->historicalPrice = $historicalPrice;
        $this->configuration = $configuration;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        if ($this->configuration->isHistoricalPriceActive()) {
            try {
                $this->historicalPrice->cleanUpHistoricalPrices();
            } catch (CouldNotSaveException|InputException|StateException $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }
}

