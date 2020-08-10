<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\View\LayoutFactory;

/**
 * Class Data
 *
 * @package Kemana\Labelmanager\Helper
 */
class Data extends AbstractHelper
{
    const CONFIG_PREFIX = 'labelmanager/general';
    const XML_PATH_LABEL_POSITION = 'labelmanager/general/label_position';
    const XML_PATH_LABEL_DISPLAY_STYLE = 'labelmanager/general/list_style';
    const XML_PATH_LABEL_LAYOUT_CONTAINER = 'labelmanager/general/layout_container';
    const XML_PATH_LABEL_SHOW_SALES = 'labelmanager/general/show_sales_label_based_on_special_price';
    const XML_PATH_LABEL_SALES_CSS = 'labelmanager/general/sale_label_style';
    const XML_PATH_LABEL_SALES_TEXT = 'labelmanager/general/sale_label_text';
    const XML_PATH_LABEL_SALES_SORT_POSITION = 'labelmanager/general/sale_label_position';
    const XML_PATH_LABEL_SALES_DISPLAY_PERC = 'labelmanager/general/show_sales_label_discount_perc';
    const XML_PATH_LABEL_SALES_DISPLAY_AMNT = 'labelmanager/general/show_sales_label_discount_amnt';
    const XML_PATH_LABELS_COUNT = 'labelmanager/general/number_of_labels_in_prod_detail';
    const XML_PATH_LABELS_COUNT_IN_LISTING = 'labelmanager/general/number_of_labels_in_cat';
    const XML_PATH_SHOW_SALE_LABEL = 'labelmanager/general/show_sales_label_based_on_special_price';
    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var string
     */
    protected $storeScope;

    /**
     * @var Reader
     */
    protected $dirReader;

    /**
     * Data constructor.
     * @param LayoutFactory $layoutFactory
     * @param Context $context
     * @param Reader $dirReader
     */
    public function __construct(
        LayoutFactory $layoutFactory,
        Context $context,
        Reader $dirReader
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $this->dirReader = $dirReader;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    /**
     * Render product label in category lsiting
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return mixed
     */
    public function renderProductLabel(\Magento\Catalog\Model\Product $product)
    {
        $layout = $this->layoutFactory->create();
        $block = $layout->createBlock(
            'Kemana\Labelmanager\Block\LabelsQuickView',
            'kemana.labelquickview',
            ['data' => []]
        );
        $block->setProduct($product);
        $html = $block->toHtml();

        return $html;
    }

    /**
     * Get display postion
     * @return mixed
     */
    public function getShowLabelPosition($storeCode)
    {
        return $this->getOptionValue(self::XML_PATH_LABEL_POSITION, $storeCode);
    }

    /**
     * Get display style
     * @return mixed
     */
    public function getShowLabelStyle($storeCode)
    {
        return $this->getOptionValue(self::XML_PATH_LABEL_DISPLAY_STYLE, $storeCode);
    }

    /**
     * Product detail page container
     * @return mixed|null
     */
    public function getLayoutContainer()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LABEL_LAYOUT_CONTAINER, $this->storeScope);
    }

    /**
     * Show sales auto
     * @return mixed|null
     */
    public function isShowSalesAuto($storeCode)
    {
        $isShow = $this->getOptionValue(self::XML_PATH_LABEL_SHOW_SALES, $storeCode);
        return $isShow == 1 ? true : false;
    }

    /**
     * Get custom  CSS
     * @return mixed|null
     */
    public function getSalesCSS($storeCode)
    {
        $css = $this->getOptionValue(self::XML_PATH_LABEL_SALES_CSS, $storeCode);
        return ($css != null ) ? $css. '!important' : '';
    }

    /**
     * Get sales label text
     * @return mixed|null
     */
    public function getSalesLabelText($storeCode)
    {
        $text = $this->getOptionValue(self::XML_PATH_LABEL_SALES_TEXT, $storeCode);
        return ($text==null) ? '' : $text;
    }

    /**
     * Get sale label postion
     * @return mixed|null
     */
    public function getSalesLabelSortPosition($storeCode)
    {
        $labelPosition = $this->getOptionValue(self::XML_PATH_LABEL_SALES_SORT_POSITION, $storeCode);
        return $labelPosition ? $labelPosition : 1 ;
    }

    /**
     * Display percentage
     * @return bool
     */
    public function isShowDisplayPercentage($storeCode)
    {
        $labelDisplayPerc = $this->scopeConfig
            ->getValue(self::XML_PATH_LABEL_SALES_DISPLAY_PERC, $this->storeScope, $storeCode);
        return $labelDisplayPerc ? true : false;
    }

    /**
     * Display amnt
     * @return bool
     */
    public function isShowDisplayAmnt($storeCode)
    {
        $labelDisplayAmnt = $this->scopeConfig
            ->getValue(self::XML_PATH_LABEL_SALES_DISPLAY_AMNT, $this->storeScope, $storeCode);
        return $labelDisplayAmnt ? true : false;
    }

    /**
     * Get number of labels to show in a product detail
     * @return Int
     */
    public function getNumberOfLabelsToShowInDetailPage($storeCode)
    {
        $count = $this->getOptionValue(self::XML_PATH_LABELS_COUNT, $storeCode);
        return ($count == null) ? 1 : $count;
    }

    /**
     * Get number of labels to show in a product listing
     * @return Int
     */
    public function getNumberOfLabelsToShowInListingPage($storeCode)
    {
        $cnt = $this->getOptionValue(self::XML_PATH_LABELS_COUNT_IN_LISTING, $storeCode);
        return ($cnt == null) ? 1 : $cnt;
    }

    /**
     * Get show/hide sales label option for Special Price
     * @return int
     */
    public function getShowSalesLabelBasedOnSpecialPrice($storeCode)
    {
        $ss = $this->getOptionValue(self::XML_PATH_SHOW_SALE_LABEL, $storeCode);
        return ($ss == null) ? false : $ss;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function getOptionValue($field, $storeCode)
    {
        return $this->scopeConfig->getValue($field, $this->storeScope, $storeCode);
    }

    /**
     * @return array
     */
    public function getLabelManagerOptions($storeCode)
    {
        return [
            'displayPosition' => $this->getShowLabelPosition($storeCode),
            'displayStyle' => $this->getShowLabelStyle($storeCode),
            'maxLabelsPdp' => $this->getNumberOfLabelsToShowInDetailPage($storeCode),
            'pdpContainer' => $this->getLayoutContainer(),
            'maxLabelsListing' =>  $this->getNumberOfLabelsToShowInListingPage($storeCode),
            'saleLabelBasedOnSpecPrice' => $this->isShowSalesAuto($storeCode),
            'showSaleLabelDescPerc' => $this->isShowDisplayPercentage($storeCode),
            'showSaleDiscAmnt' => $this->isShowDisplayAmnt($storeCode),
            'saleLabelText' => $this->getSalesLabelText($storeCode),
            'saleLabelPosition' => $this->getSalesLabelSortPosition($storeCode),
            'salesCSS' => $this->getSalesCSS($storeCode)
        ];
    }
}
