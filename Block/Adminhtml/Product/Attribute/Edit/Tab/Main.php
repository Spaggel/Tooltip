<?php
namespace Spaggel\Tooltip\Block\Adminhtml\Product\Attribute\Edit\Tab;

use Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Main as CoreMain;

class Main extends CoreMain
{
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $form = $this->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $fieldset->addField(
            'tooltip',
            'text',
            [
                'name'     => 'tooltip',
                'label'    => __('Tooltip'),
                'title'    => __('Tooltip'),
            ]
        );

        return $this;
    }
}
