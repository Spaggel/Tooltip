<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Plugin\Catalog\Product\View;

use Magento\Catalog\Block\Product\View\Attributes;

class AttributesPlugin
{
    public function afterGetAdditionalData(Attributes $subject, array $data): array
    {
        $product    = $subject->getProduct();
        $attributes = $product->getAttributes();
        foreach ($attributes as $attribute) {
            if (isset($data[$attribute->getAttributeCode()])) {
                $data[$attribute->getAttributeCode()]['tooltip'] = $attribute->getData('tooltip');
            }
        }

        return $data;
    }
}
