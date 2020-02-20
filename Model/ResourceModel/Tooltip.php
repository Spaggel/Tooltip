<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class Tooltip extends AbstractDb
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(Context $context, StoreManagerInterface $storeManager, $connectionName = null)
    {
        parent::__construct($context, $connectionName);
        $this->storeManager = $storeManager;
    }

    protected function _construct(): void
    {
        $this->_init('spaggel_tooltips', 'tooltip_id');
    }

    public function loadAttributeDefaultTooltip(int $attributeId): string
    {
        $connection = $this->getConnection();
        $bind       = [':attribute_id' => $attributeId, 'store_id' => Store::DEFAULT_STORE_ID];
        $select     = $connection->select()->from(
            $this->getTable('spaggel_tooltips'),
            ['tooltip']
        )->where(
            'attribute_id = :attribute_id AND store_id = :store_id'
        );

        $defaultTooltip = $connection->fetchOne($select, $bind);
        if ($defaultTooltip === false) {
            return '';
        }

        return $defaultTooltip;
    }

    public function loadAttributeStoreTooltips(int $attributeId, bool $withDefault = false): array
    {
        $connection = $this->getConnection();
        $bind       = [':attribute_id' => $attributeId, 'store_id' => Store::DEFAULT_STORE_ID];
        $select     = $connection->select()->from(
            $this->getTable('spaggel_tooltips'),
            ['store_id', 'tooltip']
        )->where(
            'attribute_id = :attribute_id'
        );
        if (!$withDefault) {
            $select->where('store_id <> :store_id');
        }

        return $connection->fetchPairs($select, $bind);
    }

    /**
     * @param int   $attributeId
     * @param array $storeTooltips
     *
     * @return Tooltip
     * @see \Magento\Eav\Model\ResourceModel\Entity\Attribute::_saveStoreLabels
     */
    public function saveStoreTooltips(int $attributeId, array $storeTooltips): self
    {
        $connection = $this->getConnection();
        $condition  = [
            'attribute_id = ?' => $attributeId,
            'store_id <> ?'    => Store::DEFAULT_STORE_ID
        ];
        $connection->delete($this->getTable('spaggel_tooltips'), $condition);
        foreach ($storeTooltips as $storeId => $tooltip) {
            if ($storeId === Store::DEFAULT_STORE_ID || $tooltip === '') {
                continue;
            }
            $bind = ['attribute_id' => $attributeId, 'store_id' => $storeId, 'tooltip' => $tooltip];
            $connection->insert($this->getTable('spaggel_tooltips'), $bind);
        }

        return $this;
    }

    public function loadTooltips(array $attributeIds): array
    {
        $storeId         = $this->storeManager->getStore()->getId();
        $defaultTooltips = $this->loadTooltipsFromStore($attributeIds, Store::DEFAULT_STORE_ID);
        $storeTooltips   = $this->loadTooltipsFromStore($attributeIds, $storeId);

        return array_replace($defaultTooltips, $storeTooltips);
    }

    private function loadTooltipsFromStore(array $attributeIds, int $storeId): array
    {
        $connection = $this->getConnection();
        $select     = $connection->select()->from(
            $this->getTable('spaggel_tooltips'),
            ['attribute_id', 'tooltip']
        )->where('attribute_id IN (?)', $attributeIds)
            ->where('store_id = ?', $storeId);

        return $connection->fetchPairs($select);
    }
}
