<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Controller\Adminhtml\Labels;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Kemana\Labelmanager\Controller\Adminhtml\Labels;
use Kemana\Labelmanager\Model\LabelsFactory;
use Kemana\Labelmanager\Model\OptionsFactory;

/**
 * Class Delete
 *
 * @package Kemana\Labelmanager\Controller\Adminhtml\Labels
 */
class Delete extends Labels
{
    /**
     * Object Manager
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    protected $_coreRegistry;

    /**
     * Delete constructor.
     * @param Context $context
     * @param Registry $registry
     * @param PageFactory $resultPageFactory
     * @param LabelsFactory $labelsFactory
     * @param OptionsFactory $optionsFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        PageFactory $resultPageFactory,
        LabelsFactory $labelsFactory,
        OptionsFactory $optionsFactory
    ) {
        //$this->_coreRegistry = $registry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_labelsFactory = $labelsFactory;
        $this->_optionsFactory = $optionsFactory;
        parent::__construct($context, $registry, $resultPageFactory, $labelsFactory, $optionsFactory);
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $labelId = $this->getRequest()->getParam('label_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($labelId) {
            try {
                $labelModel = $this->_labelsFactory->create();
                $optionModel = $this->_optionsFactory->create();
                $labelModel->load($labelId);

                if ($labelModel->delete()) {
                    $optionModel->load($labelId);
                    $optionModel->delete();
                }

                // display success message
                $this->messageManager->addSuccess(__('The label has been deleted.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {

                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['label_id' => $labelId]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a label to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
