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

/**
 * Class Options
 *
 * @package Kemana\Labelmanager\Model
 */
class Options extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Kemana\Labelmanager\Model\ResourceModel\Options');
    }
}
