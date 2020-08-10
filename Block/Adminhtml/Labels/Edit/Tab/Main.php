<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Block\Adminhtml\Labels\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Design\Theme\ThemePackageList;
use Magento\Store\Model\System\Store;
use Magento\Theme\Model\ResourceModel\Theme\Collection;

use Kemana\Labelmanager\Model\Attributes;
use Kemana\Labelmanager\Model\System\Config\Status;

/**
 * Class Main
 *
 * @package Kemana\Labelmanager\Block\Adminhtml\Labels\Edit\Tab
 */
class Main extends Generic implements TabInterface
{

    /**
     * @var Magento\Cms\Model\Wysiwyg\Config
     */
    protected $wysiwygConfig;
    /**
     * @var Kemana\Labelmanager\Model\System\Config\Status
     */
    protected $labelStatus;
    /**
     * @var Kemana\Labelmanager\Model\Attributes\Attributes
     */
    protected $productAttributes;

    /**
     * Declare $_themeLabelFactory
     * @var \Magento\Framework\View\Design\Theme\LabelFactory
     */
    protected $themeLabelFactory;

    /**
     * @var
     */
    protected $objectManager;

    /**
     * @var ThemePackageList
     */
    protected $themePackageList;

    /**
     * @var Magento\Framework\View\Layout\ProcessorInterface
     */
    protected $processor;

    /**
     * @var Collection
     */
    protected $themeCollection;

    /**
     * Main constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $labelStatus
     * @param Attributes $productAttributes
     * @param Store $storeManager
     * @param ThemePackageList $themePackageList
     * @param Collection $themeCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
                                FormFactory $formFactory,
                                Config $wysiwygConfig,
                                Status $labelStatus,
                                Attributes $productAttributes,
                                Store $storeManager,
                                ThemePackageList $themePackageList,
                                Collection $themeCollection,
                                 array $data = []
    ) {
        $this->wysiwygConfig = $wysiwygConfig;
        $this->labelStatus = $labelStatus;
        $this->productAttributes = $productAttributes;
        $this->themePackageList  = $themePackageList;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('labels_labels');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('labels_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Label Information')]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'label_id',
                'hidden',
                ['name' => 'label_id']
            );
        }
        $fieldset->addField(
            'name',
            'text',
            [
                'name'        => 'name',
                'label'    => __('Name'),
                'required'     => true
            ]
        );
        $fieldset->addField(
            'is_active',
            'select',
            [
                'name'      => 'is_active',
                'label'     => __('Status'),
                'options'   => $this->labelStatus->toOptionArray()
            ]
        );

        $fieldset->addField(
            'attribute_code',
            'select',
            [
                'name' => 'attribute_code',
                'label' => __('Attribute'),
                'title' => __('Attribute'),
                'required' => true,
                'options'  => $this->productAttributes->toOptionArray(),
                'note' => 'Attribute should be input type "Yes/No" and "Used in Product Listing" set as "Yes"'
            ]
        );

        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('General');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('General');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
