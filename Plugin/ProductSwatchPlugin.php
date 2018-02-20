<?php

namespace Spaggel\Tooltip\Plugin;

class ProductSwatchPlugin
{
    public function beforeSetTemplate(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        $template
    ) {
        return ['Spaggel_Tooltip::product/view/renderer.phtml'];
    }
}