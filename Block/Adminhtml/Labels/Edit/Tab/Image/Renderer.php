<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Block\Adminhtml\Labels\Edit\Tab\Image;

use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Price\Group\AbstractGroup;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Directory\Helper\Data;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context as HelperContext;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;
use Kemana\Labelmanager\Model\OptionsFactory;

/**
 * Class Renderer
 *
 * @package Kemana\Bannerslider\Block\Adminhtml\Banner\Edit\Tab\Image
 */
class Renderer extends AbstractGroup
{
    const SCOPE_STORE       = ScopeInterface::SCOPE_STORE;
    /**
     * Product Label image width
     */
    const PRODUCT_LABEL_WIDTH = 200;

    /**
     * Product Label image height
     */
    const PRODUCT_LABEL_HEIGHT = 200;

    /**
     * Category Label image width
     */
    const CATEGORY_LABEL_WIDTH = 100;

    /**
     * Category Label image height
     */
    const CATEGORY_LABEL_HEIGHT = 100;

    protected $_template    = 'image/renderer.phtml';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var OptionsFactory
     */
    protected $optionsFactory;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $imageFactory;

    /**
     * @var \Magento\Framework\Filesystem\Directory\Write
     */
    protected $mediaWriteDirectory;

    /**
     * Renderer constructor.
     * @param Context $context
     * @param GroupRepositoryInterface $groupRepository
     * @param Data $directoryHelper
     * @param Registry $registry
     * @param GroupManagementInterface $groupManagement
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CurrencyInterface $localeCurrency
     * @param HelperContext $helperContext
     * @param OptionsFactory $optionsFactory
     * @param \Magento\Framework\Image\AdapterFactory $imageFactory
     * @param array $data
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Context                     $context,
        GroupRepositoryInterface    $groupRepository,
        Data                        $directoryHelper,
        Registry                    $registry,
        GroupManagementInterface    $groupManagement,
        SearchCriteriaBuilder       $searchCriteriaBuilder,
        CurrencyInterface           $localeCurrency,
        HelperContext               $helperContext,
        OptionsFactory              $optionsFactory,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        $data = []
    ) {
        parent::__construct(
            $context,
            $groupRepository,
            $directoryHelper,
            $helperContext->getModuleManager(),
            $registry,
            $groupManagement,
            $searchCriteriaBuilder,
            $localeCurrency,
            $data
        );
        $this->optionsFactory = $optionsFactory;
        $this->mediaWriteDirectory = $context->getFilesystem()->getDirectoryWrite(DirectoryList::MEDIA);
        $this->imageFactory = $imageFactory;
        $this->storeManager = $context->getStoreManager();
        $this->scopeConfig  = $helperContext->getScopeConfig();
    }

    /**
     * Prepare global layout
     * Add "Add tier" button to layout
     * @return AbstractGroup
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            ['label' => __('Add Image'), 'onclick' => 'return tierPriceControl.addItem()', 'class' => 'add']
        );
        //$button->setName('add_tier_price_item_button');

        $this->setChild('add_button', $button);
        return parent::_prepareLayout();
    }

    /**
     * Renders grid column
     *
     * @param   \Magento\Framework\DataObject $row
     * @return  string
     */
    public function getLabelImage()
    {
        $labelId = $this->getRequest()->getParam('label_id');

        $optionModel = $this->optionsFactory->create();
        $labelsCol = $optionModel->getCollection();
        $labelsCol->addFieldToSelect('image_product')
                  ->addFieldToSelect('image_category')
            ->getSelect()->join(
                ['la'=>'ns_labelmanager_attribute'],
                'la.label_id = main_table.label_id'
            )
            ->where('la.label_id=?', $labelId);

        return $labelsCol->getData();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'labels/';
    }
}
