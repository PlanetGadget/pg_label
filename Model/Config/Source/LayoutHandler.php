<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Model\Config\Source;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\View\Layout\ProcessorFactory;
use Magento\Theme\Model\ResourceModel\Theme\Collection;

/**
 * Class LayoutHandler
 *
 * @package Kemana\Labelmanager\Model\Config\Source
 */
class LayoutHandler extends AbstractModel implements ArrayInterface
{
    /**
     * @var Collection
     */
    protected $themeCollection;

    /**
     * @var ProcessorFactory
     */
    protected $processorFactory;

    /**
     * LayoutHandler constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param Collection $themeCollection
     * @param ProcessorFactory $processorFactory
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        Collection $themeCollection,
        ProcessorFactory $processorFactory
    ) {
        $this->themeCollection  = $themeCollection;
        $this->processorFactory = $processorFactory;
        parent::__construct(
            $context,
         $registry
        );
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        $themeList = $this->themeCollection->loadRegisteredThemes()->getItems();
        $layoutHandlers  = [];
        foreach ($themeList as $theme) {
            $mergeParam = ['theme' =>$theme];
            $allContainers[] = $this->loadContainers($mergeParam);
        }

        $count = count($allContainers);
        $arrayItems = [""=>'-----Please Select A Container----','product.info.media'=>'Product Image Container'];
        //merger arrays to load multiple themes
        for ($i=0;$i<$count;$i++) {
            $arrayItems = array_merge($arrayItems, $allContainers[$i]);
        }
        return $arrayItems;
    }

    /**
     * Get Page Containers
     * @param $layoutMergeParams
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function loadContainers($layoutMergeParams)
    {
        $containers =[""=>'-----Please Select A Container----'];
        $layoutProcessor = $this->processorFactory->create($layoutMergeParams);

        $layoutProcessor->addPageHandles(['catalog_product_view']);
        $layoutProcessor->load();

        $containers = $layoutProcessor->getContainers();

        return $containers;
    }
}
