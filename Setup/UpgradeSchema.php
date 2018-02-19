<?php

namespace Spaggel\Tooltip\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1.0', '<=')) {

            $setup->getConnection()->addColumn(
                $setup->getTable('eav_attribute'),
                'tooltip',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'A simple tooltip'
                ]
            );
        }

        $setup->endSetup();
    }
}