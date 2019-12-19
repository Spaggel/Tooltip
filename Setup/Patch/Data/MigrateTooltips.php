<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Setup\Patch\Data;

use Magento\Framework\DB\Select;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\Store;
use Zend_Db_Expr;

class MigrateTooltips implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $setup;

    public function __construct(ModuleDataSetupInterface $setup)
    {
        $this->setup = $setup;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): self
    {
        if (!$this->tooltipColumnExists()) {
            return $this;
        }

        $connection        = $this->setup->getConnection();
        $eavAttributeTable = $connection->getTableName('eav_attribute');
        $tooltipsTable     = $connection->getTableName('spaggel_tooltips');
        $select            = $connection->select()
            ->from($eavAttributeTable)
            ->where('tooltip IS NOT NULL')
            ->reset(Select::COLUMNS)
            ->columns([
                'tooltip_id'   => new Zend_Db_Expr('null'),
                'attribute_id' => 'attribute_id',
                'store_id'     => new Zend_Db_Expr(Store::DEFAULT_STORE_ID),
                'tooltip'      => 'tooltip'
            ]);
        $query             = $connection->insertFromSelect($select, $tooltipsTable);
        $connection->query($query);

        return $this;
    }

    private function tooltipColumnExists(): bool
    {
        $tooltipColumnExists = false;
        $connection          = $this->setup->getConnection();
        $eavAttributeTable   = $connection->getTableName('eav_attribute');
        // disallow the ddl cache here, because we deleted the column in the DeleteOldTooltipColumn patch
        $connection->disallowDdlCache();
        if ($connection->tableColumnExists($eavAttributeTable, 'tooltip')) {
            $tooltipColumnExists = true;
        }
        $connection->allowDdlCache();

        return $tooltipColumnExists;
    }
}
