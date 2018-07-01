<?php

namespace Spaggel\Tooltip\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddTooltipToAttributeObserver implements ObserverInterface
{
    /**
     * AddTooltipToAttributeObserver constructor.
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     */
    public function __construct(\Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig)
    {
        $this->wysiwygConfig = $wysiwygConfig;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $observer->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $fieldset->addField(
            'tooltip',
            'editor',
            [
                'name' => 'tooltip',
                'label' => __('Attribute tooltip'),
                'title' => __('Attribute tooltip'),
                'config' => $this->wysiwygConfig->getConfig(),
            ]
        );
    }
}