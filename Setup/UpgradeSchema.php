<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

// @codingStandardsIgnoreFile

/**
 * Class UpgradeSchema
 *
 * @package Kemana\Labelmanager\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '2.0.8', '<')) {
            $tableName = $setup->getTable('ns_labelmanager_attribute_option');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $columns = [
                    'sort_order' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        'default' => 1,
                        'nullable' => false,
                        'comment' => 'Sort Order',
                    ]
                ];
                $this->addColumn($setup, $tableName, $columns);
            }
        }

        if (version_compare($context->getVersion(), '2.0.9', '<')) {
            $tableName = $setup->getTable('ns_labelmanager_attribute_option');
            if ($setup->getConnection()->isTableExists($tableName) == true) {

                //#NP2-2334
                $definitionFrom = [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                    'nullable' => true,
                    'comment' => 'Visible Labels From Date'
                ];
                $definitionTo = [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                    'nullable' => true,
                    'comment' => 'Visible Labels To Date'
                ];
                $this->modifyColumn($setup, $tableName, 'visible_from', $definitionFrom);
                $this->modifyColumn($setup, $tableName, 'visible_to', $definitionTo);
            }
        }

        if (version_compare($context->getVersion(), '2.0.10', '<')) {
            $tableName = $setup->getTable('ns_labelmanager_attribute_option');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $columns = [
                    'stores' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'default' => '',
                        'nullable' => true,
                        'comment' => 'store id list',
                    ]
                ];
                $this->addColumn($setup, $tableName, $columns);
            }
        }

        if (version_compare($context->getVersion(), '2.0.11', '<')) {
            $tableNameAttribute = $setup->getTable('ns_labelmanager_attribute');
            if ($setup->getConnection()->isTableExists($tableNameAttribute) == true) {
                $setup->getConnection()->dropColumn($tableNameAttribute, 'theme_id');
                $setup->getConnection()->dropColumn($tableNameAttribute, 'position');
            }

            if ($setup->getConnection()->isTableExists($tableNameAttribute) == true) {
                $columns = [
                    'layout_handler' => [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'default'  => 'product.info.media',
                        'nullable' => false,
                        'length'   => 255,
                        'comment'  => 'Layout position',
                    ]
                ];
                $this->addColumn($setup, $tableNameAttribute, $columns);

                $tableName = $setup->getTable(
                    'ns_labelmanager_attribute_option'
                );

                if ($setup->getConnection()->isTableExists($tableName)== true) {
                    $columns = [
                        'background_color' => [
                            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'default'  => '',
                            'nullable' => true,
                            'length'   => 10,
                            'comment'  => 'Label Background Color',
                        ],

                        'font_color' => [
                            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'default'  => '',
                            'nullable' => true,
                            'length'   => 10,
                            'comment'  => 'Label Text Color',
                        ]
                    ];
                    $this->addColumn($setup, $tableName, $columns);
                    //$setup->getConnection()->dropColumn($tableName,'position');
                }
            }
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param $tableName
     * @param $columns
     */
    private function addColumn(SchemaSetupInterface $setup, $tableName, $columns)
    {
        $connection = $setup->getConnection();
        foreach ($columns as $name => $definition) {
            $connection->addColumn($tableName, $name, $definition);
        }
    }

    private function modifyColumn(SchemaSetupInterface $setup, $tableName, $columnName, $definition)
    {
        $connection = $setup->getConnection();
        $connection->modifyColumn($tableName, $columnName, $definition);
    }
}
