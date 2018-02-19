<?php
namespace Spaggel\Tooltip\Block\Adminhtml\Product\Attribute\Edit\Tab;

use Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Main as CoreMain;

class Main extends CoreMain
{

    protected $_wysiwygConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Eav\Helper\Data $eavData,
        \Magento\Config\Model\Config\Source\YesnoFactory $yesnoFactory,
        \Magento\Eav\Model\Adminhtml\System\Config\Source\InputtypeFactory $inputTypeFactory,
        \Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker $propertyLocker,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $formFactory, $eavData, $yesnoFactory, $inputTypeFactory, $propertyLocker, $data);
        $this->_wysiwygConfig = $wysiwygConfig;
    }


    /**
     * Add the tooltip field to the base form
     * @return $this
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $form = $this->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $fieldset->addField(
            'tooltip',
            'editor',
            [
                'name' => 'tooltip',
                'label' => __('Attribute tooltip'),
                'title' => __('Attribute tooltip'),
                'config' => $this->_wysiwygConfig->getConfig(),
            ]
        );

        return $this;
    }
}