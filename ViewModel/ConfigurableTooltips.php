<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\ViewModel;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Spaggel\Tooltip\Model\ResourceModel\Tooltip as TooltipResource;
use Spaggel\Tooltip\Model\ResourceModel\TooltipFactory as TooltipResourceFactory;

class ConfigurableTooltips implements ArgumentInterface
{
    /**
     * @var TooltipResourceFactory
     */
    private $tooltipResourceFactory;

    public function __construct(TooltipResourceFactory $tooltipResourceFactory)
    {
        $this->tooltipResourceFactory = $tooltipResourceFactory;
    }

    public function getTooltips(iterable $attributes): array
    {
        $attributeIds = [];
        /** @var Attribute $attribute */
        foreach ($attributes as $attribute) {
            $attributeIds[] = (int)$attribute->getAttributeId();
        }
        /** @var TooltipResource $tooltipResource */
        $tooltipResource = $this->tooltipResourceFactory->create();

        return $tooltipResource->loadTooltips($attributeIds);
    }
}
