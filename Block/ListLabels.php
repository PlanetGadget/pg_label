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

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;
use Magento\Catalog\Block\Product\Context;
use Kemana\Labelmanager\Helper\Data as NsHelper;
use Kemana\Labelmanager\Model\ResourceModel\Labels\CollectionFactory as LblCollectionFactory;

/**
 * Class ListLabels
 *
 * @package Kemana\Labelmanager\Block
 */
class ListLabels extends \Magento\Catalog\Block\Product\ListProduct
{
    const XML_PATH_LABELS_COUNT = 'labelmanager/general/number_of_labels_in_cat';
    const XML_PATH_SHOW_SALE_LABEL = 'labelmanager/general/show_sales_label_based_on_special_price';

    /**
     * @var \Kemana\Labelmanager\Model\ResourceModel\Labels\LblCollectionFactory
     */
    protected $labelCollectionFactory;

    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @var int
     */
    protected $numberOfLabelsToShow = 0;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = Toolbar::class;

    /**
     * Product Collection
     *
     * @var AbstractCollection
     */
    protected $_productCollection;

    /**
     * Catalog layer
     *
     * @var Layer
     */
    protected $_catalogLayer;

    /**
     * @var PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var Data
     */
    protected $urlHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Kemana\Labelmanager\Helper\Data
     */
    protected $nsHelper;

    /**
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data $urlHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        LblCollectionFactory $labelCollectionFactory,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        NsHelper $nsHelper,
        array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->scopeConfig = $context->getScopeConfig();
        $this->storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->storeManager = $context->getStoreManager();
        $this->labelCollectionFactory = $labelCollectionFactory;
        $this->nsHelper = $nsHelper;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
    }

    /**
     * Get label data from collection
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Zend_Db_Select_Exception
     */
    public function loadLabels()
    {
        if ($this->labels == null) {
            $storeId = $this->storeManager->getStore()->getId();
            $labels = $this->labelCollectionFactory->create();
            $this->labels = $labels->withLabelOptions($storeId)->getData();
        }

        return $this->labels;
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
     * Retrieve loaded category collection
     *
     * @return AbstractCollection
     */
    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }

    /**
     * Get show/hide sales label option for Special Price
     * @return int
     */
    public function getShowSalesLabelBasedOnSpecialPrice()
    {
        $showSalesLabel = $this->scopeConfig
            ->getValue(self::XML_PATH_SHOW_SALE_LABEL, $this->storeScope);
        return $showSalesLabel;
    }

    /**
     * Get maximum labels to show in product product details page
     * @return Int
     */
    public function getMaximumLabelsToDisplayInProductListing()
    {
        return $this->nsHelper->getNumberOfLabelsToShowInListingPage();
    }
}
