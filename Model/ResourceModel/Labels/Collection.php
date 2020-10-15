<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Model\ResourceModel\Labels;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Kemana\Labelmanager\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class Collection
 *
 * @package Kemana\Labelmanager\Model\ResourceModel\Labels
 */
class Collection extends AbstractCollection
{
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var Data
     */
    protected $nsHelper;

    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Kemana\Labelmanager\Model\Labels',
            'Kemana\Labelmanager\Model\ResourceModel\Labels'
        );
    }

    /**
     * Collection constructor.
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param DateTime $date
     * @param Data $nsHelper
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        DateTime $date,
        \Kemana\Labelmanager\Helper\Data $nsHelper
    ) {
        $this->nsHelper = $nsHelper;
        $this->date = $date;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager);
    }

    /**
     * @return $this|AbstractCollection
     */
    public function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->joinLeft(
            ['attribute_option' => $this->getTable('ns_labelmanager_attribute_option')],
            'attribute_option.label_id = main_table.label_id',
            [
                'stores',
                'image_product',
                'image_category'
            ]
        );
        return $this;
    }

    /**
     * Labels collection
     *
     * @param $storeId
     * @param $maxLabels
     * @return Collection
     * @throws \Zend_Db_Select_Exception
     */
    public function withLabelOptions($storeId)
    {
        $currentDate = $this->date->gmtDate('Y-m-d');

        $this->resetAttributeOptionJoin();

        $this->getSelect()->joinLeft(
            ['lao' => 'ns_labelmanager_attribute_option'],
            'lao.label_id = main_table.label_id ',
            [
                'switch_to',
                'category_label_text',
                'product_label_text',
                'product_label_tooltip_text',
                'background_color',
                'sort_order',
                'font_color',
                'image_category',
                'image_product'
            ]
        )
            ->where(
                'main_table.is_active = 1 and (lao.visible_from IS NULL OR lao.visible_from <=?) ' .
                'and (lao.visible_to IS NULL OR lao.visible_to >=?)',
                [$currentDate]
            )
            ->where('FIND_IN_SET(?,lao.stores)', $storeId)
            ->orWhere('FIND_IN_SET(?,lao.stores)', '0')
            ->order('lao.sort_order ASC');

        return $this;
    }

    /**
     * Reset the join performed in initSelect function
     * @throws \Zend_Db_Select_Exception
     */
    private function resetAttributeOptionJoin()
    {
        $fromPart = $this->getSelect()->getPart(Select::FROM);
        $columns = $this->getSelect()->getPart(Select::COLUMNS);

        if (isset($columns) && is_array($columns) && !empty($columns)) {
            foreach ($columns as $columnKey => $columnValue) {
                if (isset($columnValue[0]) && $columnValue[0] == 'attribute_option') {
                    unset($columns[$columnKey]);
                }
            }
            $this->getSelect()->setPart(Select::COLUMNS, $columns);
        }

        if (isset($fromPart['attribute_option'])) {
            unset($fromPart['attribute_option']);
            $this->getSelect()->setPart(Select::FROM, $fromPart);
        }

        return $this;
    }
}
