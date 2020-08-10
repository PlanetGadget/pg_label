<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Block\Adminhtml\Labels;

use Magento\Backend\Block\Widget\Grid as WidgetGrid;

/**
 * Class Grid
 *
 * @package Kemana\Labelmanager\Block\Adminhtml\Labels
 */
class Grid extends WidgetGrid
{

    /**
     * @param $row
     * @return bool
     */
    public function getRowUrl($row)
    {
        return false;
    }
}
