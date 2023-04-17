<?php

namespace Macopedia\OmnibusDirective\Pricing\Render;

use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\Catalog\Pricing\Render\FinalPriceBox;
use Magento\Framework\Pricing\Helper\Data;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template\Context;

class HistoricalPriceBox extends FinalPriceBox
{


    protected $_priceHelper;

    /**
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param array $data
     * @param SalableResolverInterface $salableResolver
     * @param MinimalPriceCalculatorInterface $minimalPriceCalculator
     * @param Data $priceHelper
     */
    public function __construct(
        Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        array $data = [],
        SalableResolverInterface $salableResolver = null,
        MinimalPriceCalculatorInterface $minimalPriceCalculator = null,
        Data $priceHelper
    )
    {
        $this->_priceHelper = $priceHelper;
        parent::__construct($context, $saleableItem, $price, $rendererPool, $data, $salableResolver, $minimalPriceCalculator);
    }

    public function canShowPrice(): float
    {
        $product = $this->getSaleableItem();
        $historicalPrice = $this->_priceHelper->currency($product->getData('historical_price'), false, false);

        return (int) $product->getPriceInfo()->getPrice(FinalPrice::PRICE_CODE)->getValue()
            > (int) $historicalPrice
            && (int) $historicalPrice > 0;
    }
}
