<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Plugin\ConfigurableProduct;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Spaggel\Tooltip\Model\ResourceModel\Tooltip as TooltipResource;
use Spaggel\Tooltip\Model\ResourceModel\TooltipFactory as TooltipResourceFactory;

class ConfigurableAttributeDataPlugin
{
    /**
     * @var TooltipResourceFactory
     */
    private $tooltipResourceFactory;

    public function __construct(TooltipResourceFactory $tooltipResourceFactory)
    {
        $this->tooltipResourceFactory = $tooltipResourceFactory;
    }

    public function afterGetAttributesData(
        ConfigurableAttributeData $configurableAttributeData,
        array $result,
        Product $product,
        array $options = []
    ): array {
        $attributes   = $result['attributes'];
        $attributeIds = array_keys($attributes);
        /** @var TooltipResource $tooltipResource */
        $tooltipResource = $this->tooltipResourceFactory->create();
        $tooltips        = $tooltipResource->loadTooltips($attributeIds);
        foreach ($attributeIds as $attributeId) {
            if (isset($tooltips[$attributeId])) {
                $attributes[$attributeId]['tooltip'] = $tooltips[$attributeId];
            }
        }

        $result['attributes'] = $attributes;

        return $result;
    }
}
