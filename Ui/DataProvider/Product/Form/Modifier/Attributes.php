<?php
/**
 * Copyright © Macopedia Sp. z o.o. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Macopedia\OmnibusDirective\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;

class Attributes extends AbstractModifier
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;
    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }
    /**
     * modifyData
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        return $data;
    }

    /**
     * modifyMeta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta): array
    {
        $attributes = ['historical_price_updated_at', 'historical_price'];

        foreach ($attributes as $attribute) {
            $path = $this->arrayManager->findPath($attribute, $meta, null, 'children');
            $meta = $this->arrayManager->set(
                "{$path}/arguments/data/config/disabled",
                $meta,
                true
            );
        }

        return $meta;
    }
}
