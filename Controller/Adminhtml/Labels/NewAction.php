<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Controller\Adminhtml\Labels;

use Kemana\Labelmanager\Controller\Adminhtml\Labels;

/**
 * Class NewAction
 *
 * @package Kemana\Labelmanager\Controller\Adminhtml\Labels
 */
class NewAction extends Labels
{
    /**
     * Create new news action
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
