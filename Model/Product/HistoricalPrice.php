<?php
/**
 * Copyright © Macopedia Sp. z o.o. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Macopedia\OmnibusDirective\Model\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class HistoricalPrice
{
    /**
     * @var TimezoneInterface
     */
    private $date;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        TimezoneInterface $date,
        ProductRepositoryInterface $productRepository,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->date = $date;
        $this->productRepository = $productRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param Product $product
     * @param array $origData
     * @param array $data
     * @return void
     */
    public function updateHistoricalPrice(Product $product, array $origData, array $data)
    {
        $historicalPriceValue = $this->getHistoricalPriceUpdateValue($origData, $data);

        if ($historicalPriceValue != 0) {
            $product->setData('historical_price_updated_at', $this->date->date()->format('Y-m-d'));
            $product->setData('historical_price', $historicalPriceValue);
        }
    }

    /**
     * @param array $origData
     * @param array $data
     * @return float
     */
    public function getHistoricalPriceUpdateValue(array $origData, array $data): float
    {
        if (!empty($origData['special_price']) && empty($data['special_price'])
            && $data['price'] > $origData['special_price']) {
            return (float) $origData['special_price'];
        }

        if (!empty($origData['special_price']) && empty($data['special_price']) && !empty($origData['historical_price'])
            && $origData['special_price'] < $origData['historical_price']
            && $data['price'] > $origData['historical_price']) {
            return (float) $origData['special_price'];
        }

        if (!empty($origData['historical_price'])
            && $origData['historical_price'] < $origData['price']) {
            return 0;
        }

        if ($origData['price'] < $data['price']) {
            return (float) $origData['price'];
        }

        return 0;
    }

    /**
     * @throws StateException
     * @throws CouldNotSaveException
     * @throws InputException
     */
    public function cleanUpHistoricalPrices(): void
    {
        $date = $this->date->date(
            strtotime($this->date->date()->format('Y-m-d') . '-30 days')
        )->format('Y-m-d');

        $filter = $this->filterBuilder
            ->setField('historical_price_updated_at')
            ->setConditionType('lt')
            ->setValue($date)
            ->create();

        $this->searchCriteriaBuilder->addFilters([$filter]);

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $products = $this->productRepository->getList($searchCriteria)->getItems();

        foreach ($products as $product) {
            $product->setData('historical_price_updated_at');
            $product->setData('historical_price');

            $this->productRepository->save($product);
        }
    }
}
