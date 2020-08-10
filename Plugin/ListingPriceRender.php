<?php
/**
 * Created by PhpStorm.
 * User: ajith
 * Date: 12/6/19
 * Time: 6:14 PM
 */

namespace Kemana\Labelmanager\Plugin;

use Magento\Framework\Pricing\SaleableInterface;

class ListingPriceRender
{
    public function beforeRender(
        \Magento\Framework\Pricing\Render $subject,
        $priceCode,
        SaleableInterface $saleableItem,
        array $arguments = []
    ) {
        $subject->setData('is_product_list', false);
    }
}
