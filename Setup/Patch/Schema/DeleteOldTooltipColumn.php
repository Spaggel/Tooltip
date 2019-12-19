<?php

declare(strict_types=1);

namespace Spaggel\Tooltip\Setup\Patch\Schema;

use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Spaggel\Tooltip\Setup\Patch\Data\MigrateTooltips;

class DeleteOldTooltipColumn implements SchemaPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    public function __construct(SchemaSetupInterface $schemaSetup)
    {
        $this->schemaSetup = $schemaSetup;
    }

    public static function getDependencies(): array
    {
        return [MigrateTooltips::class];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): self
    {
        $connection = $this->schemaSetup->getConnection();
        $tableName  = $connection->getTableName('eav_attribute');
        $connection->dropColumn($tableName, 'tooltip');

        return $this;
    }
}
