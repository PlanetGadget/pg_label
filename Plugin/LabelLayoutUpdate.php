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
 * Class LabelLayoutUpdate
 *
 * @package Kemana\Labelmanager\Plugin
 */
class LabelLayoutUpdate
{
    /**
     * @var \Magento\Widget\Model\ResourceModel\Layout\Update
     */
    private $update;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;
    /**
     * @var
     */
    private $itemCollectionFactory;

    /**
     * @var
     */
    private $storeManager;

    /**
     * @var Registry
     */
    private $registry;

    /***
     * @var Data
     */
    private $helper;

    /***
     * LabelLayoutUpdate constructor.
     *
     * @param \Magento\Widget\Model\ResourceModel\Layout\Update $update
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $registry
     * @param \Kemana\Labelmanager\Model\ResourceModel\Labels\CollectionFactory $itemCollectionFactory
     * @param Data $helper
     */
    public function __construct(
        \Magento\Widget\Model\ResourceModel\Layout\Update $update,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Kemana\Labelmanager\Model\ResourceModel\Labels\CollectionFactory $itemCollectionFactory,
        Data $helper
    ) {
        $this->update = $update;
        $this->dateTime = $dateTime;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->storeManager = $storeManager;
        $this->registry     = $registry;
        $this->helper       = $helper;
    }

    /**
     * Around getDbUpdateString
     *
     * @param \Magento\Framework\View\Model\Layout\Merge $subject
     * @param callable $proceed
     * @param string $handle
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetDbUpdateString(
        \Magento\Framework\View\Model\Layout\Merge $subject,
        \Closure $proceed,
        $handle
    ) {
        $result=$proceed($handle);

        if ($handle=='catalog_product_view') {
            $result=$result . $this->getXmlString();
        }
        return $result;
    }

    /**
     * Get xml build string
     *
     * @return string
     */
    protected function getXmlString()
    {
        //TODO:Look for alternative proper xml build method
        if($this->helper->isShowLabelsOnPdp($this->storeManager->getStore()->getCode())){
            $container=  $this->helper->getLayoutContainer();
            $time = $this->dateTime->timestamp();
            if (!$container) {
                return;
            }

            $xml = "<body><referenceContainer name=\"" . $container . "\">";
            $xml .= "<block class=\"Kemana\Labelmanager\Block\Labels\" name=\"ns.labels.container" . $time . "\"
        template=\"Kemana_Labelmanager::labels.phtml\" />";
            $xml .= "</referenceContainer></body>";

            return $xml;
        }
        return;

    }
}
