<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Labels
 *
 * @package Kemana\Labelmanager\Block\Adminhtml
 */
class Labels extends Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_labels';
        $this->_blockGroup = 'Kemana_Labels';
        $this->_headerText = __('Manage Labels');
        $this->_addButtonLabel = __('Add Labels');
        parent::_construct();
    }
}
