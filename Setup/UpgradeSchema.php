<?php

namespace Spaggel\Tooltip\Setup;

use \Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.1.0', '<=')) {

            $setup->getConnection()->addColumn(
                $setup->getTable('eav_attribute'),
                'tooltip',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => Table::MAX_TEXT_SIZE,
                    'nullable' => true,
                    'comment' => 'Attribute tooltip'
                ]
            );
        }

        $setup->endSetup();
    }
}