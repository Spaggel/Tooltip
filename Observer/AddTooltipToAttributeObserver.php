<?php

namespace Spaggel\Tooltip\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Ui\Component\Wysiwyg\ConfigInterface;
use Spaggel\Tooltip\Model\ResourceModel\Tooltip as TooltipResource;
use Spaggel\Tooltip\Model\ResourceModel\TooltipFactory as TooltipResourceFactory;

class AddTooltipToAttributeObserver implements ObserverInterface
{
    /**
     * @var ConfigInterface
     */
    private $wysiwygConfig;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var TooltipResourceFactory
     */
    private $tooltipResourceFactory;

    public function __construct(
        ConfigInterface $wysiwygConfig,
        RequestInterface $request,
        TooltipResourceFactory $tooltipResourceFactory
    ) {
        $this->wysiwygConfig          = $wysiwygConfig;
        $this->request                = $request;
        $this->tooltipResourceFactory = $tooltipResourceFactory;
    }

    public function execute(Observer $observer): void
    {
        /** @var TooltipResource $tooltipResource */
        $tooltipResource = $this->tooltipResourceFactory->create();
        $attributeId = (int)$this->request->getParam('attribute_id');
        $value       = $tooltipResource->loadDefaultTooltip($attributeId);
        /** @var Form $form */
        $form     = $observer->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $fieldset->addField(
            'default_tooltip',
            'editor',
            [
                'name'   => 'default_tooltip',
                'label'  => __('Default Tooltip'),
                'title'  => __('Default Tooltip'),
                'config' => $this->wysiwygConfig->getConfig(),
                'value'  => $value
            ]
        );
    }
}
