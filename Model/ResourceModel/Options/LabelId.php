<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Model\ResourceModel\Options;

use Magento\Widget\Model\Widget\Instance as WidgetInstance;

/**
 * Class LabelId
 *
 * @package Kemana\Labelmanager\Model\ResourceModel\Options
 */
class LabelId implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Declaration
     * @var WigetInstance
     */
    protected $_resourceModel;

    /**
     * Constructor
     * @param \Kemana\Labelmanager\Model\ResourceModel\Options\Collection $widgetResourceModel
     */
    public function __construct(
        Collection $widgetResourceModel
    ) {
        $this->_resourceModel = $widgetResourceModel;
    }

    /**
     * Option Array
     * @return []
     */
    public function toOptionArray()
    {
        return $this->_resourceModel->toOptionHash();
    }
}
