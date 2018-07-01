<?php

namespace Spaggel\Tooltip\Plugin;

class ProductSwatchPlugin
{
    /**
     * Override setTemplate because the path to the template is hardcoded by Magento
     * @param \Magento\Swatches\Block\Product\Renderer\Configurable $subject
     * @param $template
     * @return array
     */
    public function beforeSetTemplate(
        \Magento\Swatches\Block\Product\Renderer\Configurable $subject,
        $template
    ) {
        /**
         * Check if the template is the core one. Very strange bugs can occur when this is
         * not the case.
         */
        $templateVendor = explode('::', $template);
        if($templateVendor[0] === 'Magento_Swatches') {
            return ['Spaggel_Tooltip::product/view/renderer.phtml'];
        }

        return [$template];
    }
}