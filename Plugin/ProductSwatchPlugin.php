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
        return ['Spaggel_Tooltip::product/view/renderer.phtml'];
    }
}