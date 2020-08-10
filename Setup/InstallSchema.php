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

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 *
 * @package Kemana\Labelmanager\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'ns_labels_attribute'
         */
        //drop table when reinstall
        $installer->getConnection()->dropTable($installer->getTable('ns_labelmanager_attribute'));
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ns_labelmanager_attribute'))
            ->addColumn(
                'label_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true
            ],
                'Label Id'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                100,
                [
                'unsigned' => true
                ],
                'Name'
            )
            ->addColumn(
                'attribute_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                100,
                [
                'unsigned' => true
            ],
                'Attribute Code'
            )
            ->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                [
                'unsigned' => true,
                'nullable' => false,
                'default'  => 0
            ],
                'Status'
            )
            ->addColumn(
                'theme_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                'nullable' => false,
                'default'  => 0
            ],
                'Theme Id'
            )
            ->addIndex(
                $installer->getIdxName('ns_labelmanager_attribute', ['label_id']),
                ['label_id']
            )
            ->setComment('Ns Label Manager Attribute');

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'ns_labelmanager_attribute_option'
         */
        //drop table in case reinstall
        $installer->getConnection()->dropTable($installer->getTable('ns_labelmanager_attribute_option'));
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ns_labelmanager_attribute_option'))
            ->addColumn(
                'option_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true
            ],
                'Value Id'
            )
            ->addColumn(
                'label_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                'unsigned' => true,
                'nullable' => false,
                'default'  => '0'
            ],
                'Label Id'
            )
            ->addColumn(
                'product_label_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'unsigned' => true,
                'nullable' => true,
                'default'  => null
            ],
                'Label text to display in detail page'
            )
            ->addColumn(
                'category_label_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                175,
                [
                'nullable' => true,
                'default'  => null
            ],
                'Label text to display in category page'
            )
            ->addColumn(
                'image_product',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable' => true,
                'default'  => null
            ],
                'Product Image'
            )
            ->addColumn(
                'image_category',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable' => true,
                'default'  => null
            ],
                'Category Image'
            )
            ->addColumn(
                'position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable' => false,
                'default'  => null
            ],
                'Label Position'
            )
            ->addColumn(
                'switch_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                5,
                [
                'nullable' => false,
                'default'  => null
            ],
                'Option to show text or image'
            )
            ->addColumn(
                'visible_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                [
                'nullable' => false,
            ],
                'Visible Labels From Date'
            )
            ->addColumn(
                'visible_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                [
                'nullable' => false,
            ],
                'Visible Labels To Date'
            )
            ->addIndex(
                $installer->getIdxName('ns_labelmanager_attribute_option', ['option_id']),
                ['option_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->setComment('Ns Label Manager Attribute Options');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
