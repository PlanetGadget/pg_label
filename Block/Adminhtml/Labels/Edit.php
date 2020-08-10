<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Block\Adminhtml\Labels;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package Kemana\Labelmanager\Block\Adminhtml\Labels
 */
class Edit extends Container
{
    /**
     * @var Registry|null
     */
    protected $coreRegistry = null;

    /**
     * Edit constructor.
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(Context $context, Registry $registry, array $data = [])
    {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'label_id';
        $this->_controller = 'adminhtml_labels';
        $this->_blockGroup = 'Kemana_Labelmanager';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Label'));
        $this->buttonList->update('delete', 'label', __('Delete Label'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            10
        );
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getHeaderText()
    {
        $labelRegistry = $this->coreRegistry->registry('labels_labels');
        if ($labelRegistry->getId()) {
            $labelTitle = $this->escapeHtml($labelRegistry->getTitle());
            return __("Edit Labels '%1'", $labelTitle);
        } else {
            return __('Add Labels');
        }
    }

    /**
     * @return Container
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('post_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'post_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'post_content');
                }
            };
        ";

        return parent::_prepareLayout();
    }
}
