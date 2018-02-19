<?php

namespace Spaggel\Tooltip\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

use \Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();

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

        $setup->endSetup();
    }
}