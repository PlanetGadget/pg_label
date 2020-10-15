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
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

/**
 * Class Options
 *
 * @package Kemana\Labelmanager\Block\Adminhtml\Labels\Edit\Tab
 */
class Options extends Generic implements TabInterface
{
    /**
     * @var Store
     */
    protected $systemStore;

    /**
     * Options constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Store $storeManager
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Store $storeManager,
        FormFactory $formFactory,
        array $data = []
    ) {
        $this->systemStore       = $storeManager;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('labels_labels');

        $isElementDisabled = !$this->_isAllowedAction('Magento_Cms::save');
        $dateFormat = $this->_localeDate->getDateFormat(
            \IntlDateFormatter::SHORT
        );

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('labels_');
        $form->setFieldNameSuffix('labels');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Configuration')]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'option_id',
                'hidden',
                ['name' => 'option_id']
            );
        }
        $fieldset->addField(
            'product_label_text',
            'text',
            [
                'name'        => 'product_label_text',
                'label'    => __('Product Label Text'),
                'required'     => false
            ]
        );
        $fieldset->addField(
            'product_label_tooltip_text',
            'textarea',
            [
                'name'        => 'product_label_tooltip_text',
                'label'    => __('Product Label Tooltip Text'),
                'required'     => false
            ]
        );
        $fieldset->addField(
            'category_label_text',
            'text',
            [
                'name'        => 'category_label_text',
                'label'    => __('Category Label Text'),
                'required'     => false
            ]
        );

        $fieldset->addField(
            'background_color',
            'text',
            [
                'name'        => 'background_color',
                'label'    => __('Label Background Color'),
                'class'  => 'jscolor{hash:true,refine:false}',
                'required'     => false,
                'style' =>"width:100px;"

            ]
        );

        $fieldset->addField(
            'font_color',
            'text',
            [
                'name'        => 'font_color',
                'label'    => __('Label Text Color'),
                'class'  => 'jscolor{hash:true,refine:false}',
                'required'     => false,
                'style' =>"width:100px;"

            ]
        );

        $fieldset->addField(
            'switch_to',
            'select',
            [
                'name'      => 'switch_to',
                'label'     => __('Media Type to Show'),
                'required'  => true,
                'options'   => [
                    'text' => 'Show Text',
                    'image' => 'Show Image'
                ],
            ]
        );
        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name'        => 'sort_order',
                'label'    => __('Sort Order'),
                'required'     => false,
                'note' => 'Label sort order default value will be 1'
            ]
        );
        $fieldset->addField(
            'visible_from',
            'date',
            [
                'name'      => 'visible_from',
                'label'     => __('Visible From'),
                'date_format' => $dateFormat,
                'disabled' => $isElementDisabled,
                'class' => 'validate-date validate-date-range date-range-custom_theme-from'
            ]
        );
        $fieldset->addField(
            'visible_to',
            'date',
            [
                'name'      => 'visible_to',
                'label'     => __('Visible To'),
                'date_format' => $dateFormat,
                'disabled' => $isElementDisabled,
                'class' => 'validate-date validate-date-range date-range-custom_theme-from'
            ]
        );

        $fieldset->addField(
            'image_product',
            'text',
            ['name' => 'image_product', 'class' => 'requried-entry']
        );

        $form->getElement(
            'image_product'
        )->setRenderer(
            $this->getLayout()
                ->createBlock('Kemana\Labelmanager\Block\Adminhtml\Labels\Edit\Tab\Image\Renderer')
        );

        $elements['image_collection_JSON'] = $fieldset->addField(
            'image_collection_JSON',
            'hidden',
            ['name' => 'image_collection_JSON', 'required' => false]
        );

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'label' => __('Store'),
                    'title' => __('Store'),
                    'values' => $this->systemStore->getStoreValuesForForm(),
                    'name' => 'stores',
                    'required' => false
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'stores',
                'hidden',
                ['name' => 'stores', 'value' => $this->systemStore->getStore(true)->getId()]
            );
        }

        if ($model && $model->getLabelId()) {
            $form->setValues($model);
            $fieldset->addField(
                'post_image_product',
                'hidden',
                ['name' => 'post_image_product', 'value' => $model->getImageProduct()]
            );
        }

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
        return __('Label Options');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Label Options');
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

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
