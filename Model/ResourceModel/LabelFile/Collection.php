<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Model\ResourceModel\LabelFile;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'file_id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            \Kemana\Labelmanager\Model\LabelFile::class,
            \Kemana\Labelmanager\Model\ResourceModel\LabelFile::class
        );
    }
}
