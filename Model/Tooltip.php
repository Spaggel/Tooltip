<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Spaggel\Tooltip\Model\ResourceModel\Tooltip as TooltipResource;

class Tooltip extends AbstractModel implements IdentityInterface
{
    private const CACHE_TAG = 'spaggel_tooltip';

    protected function _construct(): void
    {
        $this->_init(TooltipResource::class);
    }

    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
