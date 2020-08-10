<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Model;

/**
 * Class LabelList
 *
 * @package Kemana\Labelmanager\Model
 */
class LabelList extends \Magento\Framework\DataObject
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ResourceModel\Labels\CollectionFactory
     */
    private $itemCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var \Magento\Directory\Model\Currency
     */
    private $currency;

    /**
     * @var \Kemana\Labelmanager\Helper\Data
     */
    private $helperData;

    /**
     * @var []
     */
    private $itemLabelData;

    /**
     * @var []
     */
    private $labelItems;

    /**
     * @var string
     */
    private $mediaPath;

    /**
     * LabelList constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ResourceModel\Labels\CollectionFactory $itemCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Directory\Model\Currency $currency
     * @param \Kemana\Labelmanager\Helper\Data $nsHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ResourceModel\Labels\CollectionFactory $itemCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Directory\Model\Currency $currency,
        \Kemana\Labelmanager\Helper\Data $nsHelper,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->currency = $currency;
        $this->helperData = $nsHelper;
        $this->_data = $data;
        parent::__construct($data);
    }

    /**
     * @param $websiteId
     * @return |null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLabelItemData($websiteId)
    {
        if ($this->itemLabelData === null) {
            $storeDetails = $this->getStoreDetailsByWebsiteId($websiteId);
            $itemLabelData = $this->getLabelAttributes()->withLabelOptions($storeDetails['store_id'])->getData();
            foreach ($itemLabelData as $itemLabel) {
                if ($itemLabel['image_category']) {
                    $itemLabel['image_category_url'] = $this->getImageFullPath($itemLabel['image_category']);
                }
                if ($itemLabel['image_product']) {
                    $itemLabel['image_product_url'] = $this->getImageFullPath($itemLabel['image_product']);
                }
                $this->itemLabelData[] = $itemLabel;
            }
            $this->labelItems = null;
        }
        return $this->itemLabelData;
    }

    /**
     * Full image path
     * @param $labelData
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getImageFullPath($labelData)
    {
        $fullPath = null;
        if (isset($labelData) && $labelData != 'null') {
            $fullPath = $this->getImagePath() . $labelData;
        }
        return $fullPath;
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
     * Get image path for label
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getImagePath()
    {
        if ($this->mediaPath === null) {
            $this->mediaPath = $this
                    ->storeManager->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'labels/';
        }
        return $this->mediaPath;
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

    /***
     * label type image/text
     *
     * @param $labelData
     *
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
     * Display discount label
     * @param $websiteId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function allLabelProducts($websiteId)
    {
        $labels = $this->getLabelAttributeCodes();
        $labelList = [];
        $products = $this->productCollectionFactory->create()->addAttributeToSelect('*');
        $storeDetails = $this->getStoreDetailsByWebsiteId($websiteId);
        $products->setStoreId($storeDetails['store_id']);
        $filters = [];
        if (count($labels) > 0) {
            foreach ($labels as $label) {
                $filters[] = [
                    'attribute'=>$label,
                    'eq' => 1
                ];
            }
            $products->addAttributeToFilter($filters, null, 'left');
            $products->addWebsiteFilter($websiteId);
            $labels = $this->getLabelItemData($websiteId);
            foreach ($products as $product) {
                $labelList[$product->getId()]['id'] = $product->getId();
                $labelList[$product->getId()]['sku'] = $product->getSku();
                $labelList[$product->getId()]['labels'] = $this
                    ->productLabelDetails($product, $labels);
            }
        }
        return $labelList;
    }

    /**
     * Display discount label
     * @param $websiteId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function allProducts()
    {
        $isShowDiscountPerc = $this->helperData->isShowDisplayPercentage();
        $isShowDiscountAmnt = $this->helperData->isShowDisplayAmnt();
        $currentCurrency = $this->storeManager->getStore()->getCurrentCurrency();
        $symbol = $currentCurrency->getCurrencySymbol();
        $currencySymbol = $symbol ? $symbol : $currentCurrency->getCode();
        $salesSortOrder = $this->helperData->getSalesLabelSortPosition();
        $labelList = [];
        $products = $this->productCollectionFactory->create()->addAttributeToSelect('*');
        $labels = $this->getLabelItemData();
        foreach ($products as $product) {
            $discountPercentage = null;
            if ($this->helperData->isShowSalesAuto()) {
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
                        $discount = "<span class='ns-label-mgr-currency-symbol'>"
                            . $currencySymbol
                            . "</span>"
                            . $discount;
                    } else {
                        $discount = ($discountAmount / $originalPrice) * 100;
                        //Round down the $discount up to two decimal points.
                        $discount = round($discount, 2);
                    }
                } else {
                    $discount = '';
                }
                $discountLabel = [];
                if ($discount && $isShowDiscountPerc && !$isShowDiscountAmnt) {
                    $discountLabel =  $this->helperData->getSalesLabelText()
                        . ' '
                        . $discount
                        . "%";
                } elseif ($discount && $isShowDiscountPerc && $isShowDiscountAmnt) {
                    $discountLabel =  $this->helperData->getSalesLabelText()
                        . ' '
                        . $discount
                        . "%";
                } elseif ($discount && $isShowDiscountAmnt) {
                    $discountLabel=  $this->helperData->getSalesLabelText()
                        . ' '
                        . $discount;
                } elseif ($discount) {
                    $discountLabel = $this->helperData->getSalesLabelText();
                }
                $discountPercentage = $discountLabel;
            }
            $labelList[$product->getId()]['id'] = $product->getId();
            $labelList[$product->getId()]['sku'] = $product->getSku();
            $labelList[$product->getId()]['labels'] = $this
                ->productLabelDetails($product, $labels, $discountPercentage, $salesSortOrder);
        }
        return $labelList;
    }

    /**
     * @param $website
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareLabelDataJson($website)
    {
        $products = $this->allLabelProducts($website);
        $storeDetails = $this->getStoreDetailsByWebsiteId($website);
        $configs = $this->helperData->getLabelManagerOptions($storeDetails['store_code']);
        $labels = $this->getLabelItemData($website);
        $data = [
            'labelProducts' => $products,
            'configs' => $configs,
            'labels' => $labels
        ];
        return json_encode($data);
    }

    /**
     * @return array
     */
    public function getLabelAttributeCodes()
    {
        $collection = $this->itemCollectionFactory->create()->getItems();
        $labelAttributes = [];
        foreach ($collection as $item) {
            if ($item->getIsActive() && $item->getAttributeCode() != '#') {
                $labelAttributes[] = $item->getAttributeCode();
            }
        }
        return $labelAttributes;
    }
    /**
     * @param $product
     * @param null $labels
     * @param null $discount
     * @param int $salesSortOrder
     * @return array
     */
    private function productLabelDetails($product, $labels = null)
    {
        $collection = [];
        if ($labels) {
            foreach ($labels as $label) {
                if ($label['is_active'] == 1 &&
                    $product->hasData($label['attribute_code']) &&
                    $product->getData($label['attribute_code']) != null
                ) {
                    $collection
                    [$label['attribute_code']] = ($product
                        ->getData($label['attribute_code'])) ? true : false;
                }
            }
        }
        return $collection;
    }

    /**
     * Get maximum labels to show in product product details page
     *
     * @return Int
     */
    public function getLabelDiscountAmount()
    {
        $isShowDiscountAmnt = $this->helperData->isShowDisplayAmnt();
        return $isShowDiscountAmnt;
    }

    /**
     * @return mixed
     */
    public function getShowLabelStyle()
    {
        return $this->helperData->getShowLabelStyle();
    }

    /**
     * @return mixed
     */
    public function getShowLabelPosition()
    {
        return $this->helperData->getShowLabelPosition();
    }

    /**
     * @return mixed|null
     */
    public function getSalesCSS()
    {
        return $this->helperData->getSalesCSS();
    }

    /**
     * @return int
     */
    public function getShowSalesLabelBasedOnSpecialPrice()
    {
        return $this->helperData->getShowSalesLabelBasedOnSpecialPrice();
    }

    /**
     * @return Int
     */
    public function getNumberOfLabelsToShowInDetailPage()
    {
        return $this->helperData->getNumberOfLabelsToShowInDetailPage();
    }

    /**
     * @return Int
     */
    public function getNumberOfLabelsToShowInListingPage()
    {
        return $this->helperData->getNumberOfLabelsToShowInListingPage();
    }

    /**
     * @return mixed
     */
    public function getSalesLabelSortPosition()
    {
        return $this->helperData->getSalesLabelSortPosition();
    }

    /**
     * @param $websiteId
     * @return array
     */
    public function getStoreDetailsByWebsiteId($websiteId)
    {
        $websiteStores = $this->storeManager->getWebsites()[$websiteId]->getStores();
        $storeDetails = [];
        foreach ($websiteStores as $websiteStore) {
            $storeDetails = [
                'store_id' => $websiteStore->getStoreId(),
                'store_code' => $websiteStore->getCode()
                ];
        }
        return $storeDetails;
    }
}
