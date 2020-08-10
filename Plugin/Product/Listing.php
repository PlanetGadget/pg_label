<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Plugin\Product;

/**
 * Class Listing
 * @package Kemana\Labelmanager\Plugin\Product
 */
class Listing
{
    /**
     * @param \Magento\Catalog\Block\Product\ListProduct $productListing
     * @param $result
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function afterGetProductDetailsHtml(
        \Magento\Catalog\Block\Product\ListProduct $productListing,
        $result,
        \Magento\Catalog\Model\Product $product
    ) {
        $result .= "<input type='hidden' name='product' class='label-id' value='{$product->getId()}'>";
        return $result;
    }
}
