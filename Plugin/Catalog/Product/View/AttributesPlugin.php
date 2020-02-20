<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Plugin\Catalog\Product\View;

use Magento\Catalog\Block\Product\View\Attributes;
use Spaggel\Tooltip\Model\ResourceModel\Tooltip as TooltipResource;
use Spaggel\Tooltip\Model\ResourceModel\TooltipFactory as TooltipResourceFactory;

class AttributesPlugin
{
    /**
     * @var TooltipResourceFactory
     */
    private $tooltipResourceFactory;

    public function __construct(TooltipResourceFactory $tooltipResourceFactory)
    {
        $this->tooltipResourceFactory = $tooltipResourceFactory;
    }

    public function afterGetAdditionalData(Attributes $subject, array $data, array $excludeAttr = []): array
    {
        $attributes = $subject->getProduct()->getAttributes();
        /** @var TooltipResource $tooltipResource */
        $tooltipResource = $this->tooltipResourceFactory->create();
        $attributeCodes  = array_keys($data);
        $attributeIds    = array_map(static function (string $attributeCode) use ($attributes) {
            return (int)$attributes[$attributeCode]->getId();
        }, $attributeCodes);
        $tooltips        = $tooltipResource->loadTooltips($attributeIds);
        foreach ($attributeCodes as $attributeCode) {
            $attributeId = (int)$attributes[$attributeCode]->getId();
            if (isset($tooltips[$attributeId])) {
                $data[$attributeCode]['tooltip'] = $tooltips[$attributeId];
            }
        }

        return $data;
    }
}
