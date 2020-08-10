<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Model\Config\Source;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class ListStyle
 *
 * @package Kemana\Labelmanager\Model\Config\Source
 */
class ListStyle extends AbstractModel implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $listStyles = [
            ""=>'---Select A List Style---',
            'horizontal' => 'Horizontal',
            'vertical' => 'Vertical'
        ];
        $ret = [];
        foreach ($listStyles as $key => $value) {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $ret;
    }
}
