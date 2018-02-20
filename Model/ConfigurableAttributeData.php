<?php

namespace Spaggel\Tooltip\Model;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute;

/**
 * Class ConfigurableAttributeData
 * @api
 * @since 100.0.2
 */
class ConfigurableAttributeData extends \Magento\ConfigurableProduct\Model\ConfigurableAttributeData
{
    /**
     * Get product attributes
     *
     * @param Product $product
     * @param array $options
     * @return array
     */
    public function getAttributesData(Product $product, array $options = [])
    {
        $defaultValues = [];
        $attributes = [];
        foreach ($product->getTypeInstance()->getConfigurableAttributes($product) as $attribute) {
            $attributeOptionsData = $this->getAttributeOptionsData($attribute, $options);
            if ($attributeOptionsData) {
                $productAttribute = $attribute->getProductAttribute();
                $attributeId = $productAttribute->getId();
                $attributes[$attributeId] = [
                    'id' => $attributeId,
                    'code' => $productAttribute->getAttributeCode(),
                    'label' => $productAttribute->getStoreLabel($product->getStoreId()),
                    'options' => $attributeOptionsData,
                    'position' => $attribute->getPosition(),
                    'tooltip' => $productAttribute->getData('tooltip') ?: '',
                ];
                $defaultValues[$attributeId] = $this->getAttributeConfigValue($attributeId, $product);
            }
        }
        return [
            'attributes' => $attributes,
            'defaultValues' => $defaultValues,
        ];
    }
}
