<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Block\Adminhtml\Catalog\Product\Attribute\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Catalog\Model\Entity\Attribute;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Wysiwyg\ConfigInterface;
use Spaggel\Tooltip\Model\ResourceModel\Tooltip as TooltipResource;
use Spaggel\Tooltip\Model\ResourceModel\TooltipFactory as TooltipResourceFactory;

class Tooltips extends Generic implements TabInterface
{
    /**
     * @var ConfigInterface
     */
    private $wysiwygConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var TooltipResourceFactory
     */
    private $tooltipResourceFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        ConfigInterface $wysiwygConfig,
        StoreManagerInterface $storeManager,
        TooltipResourceFactory $tooltipResourceFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->wysiwygConfig          = $wysiwygConfig;
        $this->storeManager           = $storeManager;
        $this->tooltipResourceFactory = $tooltipResourceFactory;
    }

    public function getTabLabel(): string
    {
        return __('Tooltips')->render();
    }

    public function getTabTitle(): string
    {
        return __('Tooltips')->render();
    }

    public function canShowTab(): bool
    {
        return true;
    }

    public function isHidden(): bool
    {
        return false;
    }

    public function getAfter()
    {
        return 'front';
    }

    protected function _prepareForm(): self
    {
        /** @var Attribute $attributeObject */
        $attributeObject = $this->_coreRegistry->registry('entity_attribute');
        /** @var TooltipResource $tooltipResource */
        $tooltipResource = $this->tooltipResourceFactory->create();
        $attributeId     = (int)$attributeObject->getAttributeId();
        $storeTooltips   = $tooltipResource->loadAttributeStoreTooltips($attributeId);

        /** @var Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset(
            'spaggel_tooltip_fieldset',
            ['legend' => __('Tooltips'), 'collapsable' => $this->getRequest()->has('popup')]
        );

        foreach ($this->storeManager->getStores(false) as $store) {
            $label = __('Tooltip For Store %1 (ID %2)', $store->getName(), $store->getId());
            $value = $storeTooltips[$store->getId()] ?? '';
            $fieldset->addField(
                'tooltip_' . $store->getId(),
                'editor',
                [
                    'name'   => 'tooltips[' . $store->getId() . ']',
                    'label'  => $label,
                    'title'  => $label,
                    'config' => $this->wysiwygConfig->getConfig(),
                    // this does not work :(
                    'value'  => $value
                ]
            );
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
