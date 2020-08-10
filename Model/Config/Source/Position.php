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
 * Class Position
 *
 * @package Kemana\Labelmanager\Model\Config\Source
 */
class Position extends AbstractModel implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $optPositions = [
            ""              => '---Select A Position---',
            'top-left'      => 'Top Left',
            'top-center'    => 'Top Center',
            'top-right'     => 'Top Right',
            'bottom-left'   => 'Bottom Left',
            'bottom-center' => 'Bottom Center',
            'bottom-right'  => 'Bottom Right'
        ];
        $ret = [];
        foreach ($optPositions as $key => $value) {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $ret;
    }
}
