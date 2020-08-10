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

use Magento\Directory\Model\Currency;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

use Kemana\Labelmanager\Helper\Data;

/**
 * Class Labels
 *
 * @package Kemana\Labelmanager\Block
 */
class Labels extends Template
{
    const XML_PATH_SHOW_SALE_LABEL = 'labelmanager/general/show_sales_label_based_on_special_price';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Kemana\Labelmanager\Model\ResourceModel\Labels\CollectionFactory
     */
    protected $itemCollectionFactory;

    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var base media Path
     */
    protected $mediaPath;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var
     */
    protected $storeScope;

    /**
     * @var
     */
    protected $labelItems;

    /***
     * @var
     */
    protected $itemLabelData;

    /**
     * @var \Magento\Catalog\Pricing\Render\FinalPriceBox
     */
    protected $priceBox;

    /**
     * @var Kemana\Labelmanager\Helper\Data
     */
    protected $nsHelper;

    /**
     * @var
     */
    protected $currency;

    private $viewModel;

    /**
     * Banner constructor.
     *
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Kemana\Labelmanager\Model\ResourceModel\Labels\CollectionFactory $itemCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param Currency $currency
     * @param Data $nsHelper
     * @param \Kemana\Labelmanager\ViewModel\FileManager $viewModel
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Kemana\Labelmanager\Model\ResourceModel\Labels\CollectionFactory $itemCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        Currency $currency,
        Data $nsHelper,
        \Kemana\Labelmanager\ViewModel\FileManager $viewModel
    ) {
        $this->registry = $registry;
        $this->storeManager = $context->getStoreManager();
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->scopeConfig = $context->getScopeConfig();
        $this->storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->nsHelper = $nsHelper;
        $this->currency = $currency;
        $this->viewModel = $viewModel;
        parent::__construct($context);
    }

    /**
     * Get label data from collection
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLabelItemData()
    {
        if ($this->itemLabelData === null) {
            $storeId = $this->storeManager->getStore()->getId();
            $this->itemLabelData = $this->getLabelAttributes()->withLabelOptions($storeId)->getData();
        }
        return $this->itemLabelData;
    }

    /**
     * Get label attributes
     * @return mixed
     */
    public function getLabelAttributes()
    {
        if ($this->labelItems === null) {
            $this->labelItems = $this->itemCollectionFactory->create();
        }

        return $this->labelItems;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLabelsFile()
    {
        return $this->viewModel->getLabelDataFile();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencySymbol()
    {
        return $this->viewModel->getCurrencySymbol();
    }

    /**
     * @param $productId
     * @return array|string
     */
    public function getProductType($productId)
    {
        return $this->viewModel->getProductType($productId);
    }

    /**
     * @return base|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getImagePath()
    {
        if ($this->mediaPath === null) {
            $this->mediaPath = $this->storeManager->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'labels/';
        }

        return $this->mediaPath;
    }

    /**
     * get current product
     * @return mixed
     */
    public function getCurrentProduct()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $currentProduct = $objectManager->get('Magento\Framework\Registry')->registry('current_product');
        return $currentProduct;
    }

    /**
     * Get label item data
     *
     * @param $labelItemData
     * @return string
     */
    public function getLabelTextSpan($labelItemData)
    {
        return $spanClass = strtolower(str_replace(' ', '_', $labelItemData['product_label_text']));
    }

    /**
     * @param $labelData
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageFullPath($labelData)
    {
        $fullPath = null;
        if (isset($labelData['image_product']) && $labelData['image_product'] != 'null') {
            $fullPath = $this->getImagePath() . $labelData['image_product'];
        }

        return $fullPath;
    }

    /**
     * @param $labelData
     * @return bool
     */
    public function isImageLabel($labelData)
    {
        if ($labelData['switch_to'] == 'image') {
            return true;
        }
        return false;
    }

    /**
     * Get Kemana\Labelmanager\Helper\Data
     *
     * @return Data
     */
    public function getHelper()
    {
        return $this->nsHelper;
    }

    /**
     * Display discount label
     *
     * @param $product
     * @return bool|mixed|string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function displayDiscountLabel($product)
    {
        $isShowDiscountPerc = $this->nsHelper->isShowDisplayPercentage();
        $isShowDiscountAmnt = $this->nsHelper->isShowDisplayAmnt();
        $currentCurrency = $this->storeManager->getStore()->getCurrentCurrency();
        $symbol = $currentCurrency->getCurrencySymbol();
        $currencySymbol = $symbol ? $symbol : $currentCurrency->getCode();

        if ($this->nsHelper->isShowSalesAuto()) {
            $originalPrice = $product->getPriceInfo()->getPrice('regular_price')
                ->getAmount()->getValue();
            $finalPrice = $product->getPriceInfo()->getPrice('final_price')
                ->getAmount()->getValue();
            $discount = 0;
            if ($originalPrice > $finalPrice) {
                $discountAmount = $originalPrice - $finalPrice;
                if ($isShowDiscountAmnt && !$isShowDiscountPerc) {
                    if (strpos($discountAmount, ".") !== false) {
                        $discount = $this->currency
                            ->format(
                                $discountAmount,
                                ['display' => \Zend_Currency::NO_SYMBOL],
                                false,
                                false
                            );
                    } else {
                        $discount = $this->currency
                            ->formatPrecision(
                                $discountAmount,
                                0,
                                ['display' => \Zend_Currency::NO_SYMBOL],
                                false,
                                false
                            );
                    }
                    $discount = "<span class='ns-label-mgr-currency-symbol'>" . $currencySymbol . "</span>" . $discount;
                } else {
                    $discount = ($discountAmount / $originalPrice) * 100;
                    //Round down the $discount up to two decimal points.
                    $discount = floor($discount * 100) / 100;
                }
            } else {
                $discount = '';
            }
            if ($discount && $isShowDiscountPerc && !$isShowDiscountAmnt) {
                return $this->nsHelper->getSalesLabelText() . ' ' . $discount . "%";
            } elseif ($discount && $isShowDiscountPerc && $isShowDiscountAmnt) {
                return $this->nsHelper->getSalesLabelText() . ' ' . $discount . "%";
            } elseif ($discount && $isShowDiscountAmnt) {
                return $this->nsHelper->getSalesLabelText() . ' ' . $discount;
            } elseif ($discount) {
                return $this->nsHelper->getSalesLabelText();
            }
        }
        return false;
    }

    /**
     * Get maximum labels to show in product product details page
     *
     * @return Int
     */
    public function getLabelDiscountAmount()
    {
        $isShowDiscountAmnt = $this->nsHelper->isShowDisplayAmnt();
        return $isShowDiscountAmnt;
    }
}
