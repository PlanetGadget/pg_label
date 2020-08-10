<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Plugin;

use Kemana\Labelmanager\Helper\Data;

/**
 * Class LabelQuickView
 *
 * @package Kemana\Labelmanager\Plugin
 */
class LabelQuickView
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * LabelQuickView constructor.
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /***
     * @param \Kemana\Quickviewclassic\Block\Gallery $subject
     * @param                                            $result
     *
     * @return string
     */
    public function afterToHtml(\Kemana\Quickviewclassic\Block\Gallery $subject, $result)
    {
        $result .= $this->helper->renderProductLabel($subject->getProduct());
        return $result;
    }
}
