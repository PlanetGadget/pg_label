<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Model\ResourceModel\Image;

/**
 * Class ProductImage
 *
 * @package Kemana\Labelmanager\Model\ResourceModel\Image
 */
class ProductImage implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var TransactionRepositoryInterface
     */
    protected $transactionRepository;

    /**
     * ProductImage constructor.
     * @param \Kemana\Labelmanager\Model\ResourceModel\Options\Collection $widgetResourceModel
     */

    public function __construct(
        \Kemana\Labelmanager\Model\ResourceModel\Options\Collection $widgetResourceModel
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
