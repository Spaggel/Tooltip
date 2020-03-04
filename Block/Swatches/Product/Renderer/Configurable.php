<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Block\Swatches\Product\Renderer;

use Magento\Swatches\Block\Product\Renderer\Configurable as ParentConfigurable;

class Configurable extends ParentConfigurable
{
    public const CONFIGURABLE_RENDERER_TEMPLATE = 'Spaggel_Tooltip::product/view/type/options/configurable.phtml';

    protected function getRendererTemplate(): string
    {
        return $this->isProductHasSwatchAttribute() ?
            self::SWATCH_RENDERER_TEMPLATE : self::CONFIGURABLE_RENDERER_TEMPLATE;
    }
}
