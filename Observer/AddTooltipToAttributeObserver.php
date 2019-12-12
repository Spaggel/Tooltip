<?php

namespace Spaggel\Tooltip\Observer;

use Magento\Framework\Data\Form;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Ui\Component\Wysiwyg\ConfigInterface;

class AddTooltipToAttributeObserver implements ObserverInterface
{
    /**
     * @var ConfigInterface
     */
    private $wysiwygConfig;

    /**
     * @param ConfigInterface $wysiwygConfig
     */
    public function __construct(ConfigInterface $wysiwygConfig)
    {
        $this->wysiwygConfig = $wysiwygConfig;
    }

    public function execute(Observer $observer): void
    {
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
            ]
        );
    }
}
