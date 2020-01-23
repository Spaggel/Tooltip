<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Observer;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\Store;
use Spaggel\Tooltip\Model\ResourceModel\Tooltip as TooltipResource;
use Spaggel\Tooltip\Model\ResourceModel\Tooltip\CollectionFactory as TooltipCollectionFactory;
use Spaggel\Tooltip\Model\ResourceModel\TooltipFactory as TooltipResourceFactory;
use Spaggel\Tooltip\Model\Tooltip;

class StoreTooltipsObserver implements ObserverInterface
{
    /**
     * @var TooltipResourceFactory
     */
    private $tooltipResourceFactory;

    /**
     * @var TooltipCollectionFactory
     */
    private $tooltipCollectionFactory;

    public function __construct(
        TooltipResourceFactory $tooltipResourceFactory,
        TooltipCollectionFactory $tooltipCollectionFactory
    ) {
        $this->tooltipResourceFactory   = $tooltipResourceFactory;
        $this->tooltipCollectionFactory = $tooltipCollectionFactory;
    }

    public function execute(Observer $observer): void
    {
        /** @var Attribute $attribute */
        $attribute   = $observer->getData('attribute');
        $attributeId = (int)$attribute->getId();
        /** @var TooltipResource $tooltipResource */
        $tooltipResource = $this->tooltipResourceFactory->create();
        /** @var Tooltip $defaultTooltip */
        $defaultTooltip        = $this->tooltipCollectionFactory
            ->create()
            ->addFieldToFilter('attribute_id', $attributeId)
            ->addFieldToFilter('store_id', Store::DEFAULT_STORE_ID)
            ->getFirstItem();
        $defaultTooltipContent = $attribute->getData('default_tooltip');
        if (empty($defaultTooltipContent) && !$defaultTooltip->isObjectNew()) {
            $tooltipResource->delete($defaultTooltip);
        }

        if ($defaultTooltipContent !== '') {
            $defaultTooltip->setData('attribute_id', $attributeId);
            $defaultTooltip->setData('store_id', Store::DEFAULT_STORE_ID);
            $defaultTooltip->setData('tooltip', $defaultTooltipContent);
            $tooltipResource->save($defaultTooltip);
        }

        $storeTooltips = $attribute->getData('tooltips') ?? [];
        $tooltipResource->saveStoreTooltips($attributeId, $storeTooltips);
    }
}
