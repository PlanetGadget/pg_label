<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;

/**
 * Class Labels
 *
 * @package Kemana\Labelmanager\Model
 */
class Labels extends \Magento\Framework\Model\AbstractModel
{
    /**
     * url builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    /**
     * define resource model
     */
    protected function _construct()
    {
        $this->_init('Kemana\Labelmanager\Model\ResourceModel\Labels');
    }

    /**
     * Retrieve Full Option values array
     *
     * @param bool $withEmpty Add empty option to array
     * @param bool $defaultValues
     * @return []
     */
    public function getAllOptions($attrResultArray, $withEmpty = true, $defaultValues = false)
    {
        $attribute = current($attrResultArray)->getData();
        $storeId = $this->storeManager->getStore()->getId();
        if (!is_array($this->_options)) {
            $this->_options = [];
        }
        if (!is_array($this->_optionsDefault)) {
            $this->_optionsDefault = [];
        }
        if (!isset($this->_options[$storeId])) {
            $collection = $this->_attrOptionCollectionFactory->create()->setSortOrder(
                'asc'
            )->setAttributeFilter(
                $attribute['attribute_id']
            )->setStoreFilter(
                $storeId
            )->load();
            $this->_options[$storeId] = $collection->toOptionArray();
            $this->_optionsDefault[$storeId] = $collection->toOptionArray('default_value');
        }
        $options = $defaultValues ? $this->_optionsDefault[$storeId] : $this->_options[$storeId];
        if ($withEmpty) {
            array_unshift($options, ['label' => '', 'value' => '']);
        }

        return $options;
    }
}
