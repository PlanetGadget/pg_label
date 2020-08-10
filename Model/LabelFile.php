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

class LabelFile extends \Magento\Framework\Model\AbstractModel
{
    /**
     * define resource model
     */
    protected function _construct()
    {
        $this->_init(\Kemana\Labelmanager\Model\ResourceModel\LabelFile::class);
    }
}
