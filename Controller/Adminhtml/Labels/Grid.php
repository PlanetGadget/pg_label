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
 * Class Grid
 *
 * @package Kemana\Labelmanager\Controller\Adminhtml\Labels
 */
class Grid extends Labels
{
    /**
     * @return void
     */
    public function execute()
    {
        return $this->_resultPageFactory->create();
    }
}
