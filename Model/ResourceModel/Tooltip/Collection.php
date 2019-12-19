<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Model\ResourceModel\Tooltip;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Spaggel\Tooltip\Model\ResourceModel\Tooltip as TooltipResource;
use Spaggel\Tooltip\Model\Tooltip;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(Tooltip::class, TooltipResource::class);
    }
}
