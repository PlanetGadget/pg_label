<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\Context;

/**
 * Class LabelsQuickView
 *
 * @package Kemana\Labelmanager\Block
 */
class LabelsQuickView extends Template
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * @var \Kemana\Labelmanager\ViewModel\FileManager
     */
    private $viewModel;

    /**
     * LabelsQuickView constructor.
     * @param Context $context
     * @param \Kemana\Labelmanager\ViewModel\FileManager $viewModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Kemana\Labelmanager\ViewModel\FileManager $viewModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->viewModel = $viewModel;
        $this->setTemplate('Kemana_Labelmanager::labels_quickview.phtml');
    }

    /**
     * Get product details block
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductDetailBlock()
    {
        return $this->getLayout()->createBlock('Kemana\Labelmanager\Block\Labels');
    }

    /**
     * Get labels list block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLabelsListBlock()
    {
        return $this->getLayout()->createBlock('Kemana\Labelmanager\Block\ListLabels');
    }

    /**
     * @inheritdoc
     */
    public function setProduct($product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Get the product currently view
     *
     * @return \Magento\Catalog\Model\Product|mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Return Label Data Json file path
     * @return string
     */
    public function getLabelsFile()
    {
        return $this->viewModel->getLabelDataFile();
    }
}
