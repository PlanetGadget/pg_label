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
 * Class Edit
 *
 * @package Kemana\Labelmanager\Controller\Adminhtml\Labels
 */
class Edit extends Labels
{
    /**
     * Object Manager
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    protected $coreRegistry;

    /**
     * Edit constructor.
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
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $registry, $resultPageFactory, $labelsFactory, $optionsFactory);
    }

    /**
     * @return \Magento\Framework\View\Result\Page|void
     */
    public function execute()
    {
        $labelId = $this->getRequest()->getParam('label_id');
        $model = $this->_objectManager->create('Kemana\Labelmanager\Model\Labels');

        if ($labelId) {
            $model->load($labelId);
            if (!$model->getLabelId()) {
                $this->messageManager->addError(__('This label no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        //join label attribute option table to get option data
        $optionsCol = $model->getCollection();
        $optionsCol->addFieldToSelect('*');
        $optionsCol->getSelect()->join(
            ['lap'=>'ns_labelmanager_attribute_option'],
            'lap.label_id = main_table.label_id'
        )
            ->where('main_table.label_id=?', $labelId);

        $data = $optionsCol->getData();

        //$model->setData($data);
        if (!empty($data)) {
            $model->setData($data[0]); // push data into model
        }

        $this->_coreRegistry->register('labels_labels', $model);

        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Kemana_Labelmanager::labels');
        $resultPage->getConfig()->getTitle()->prepend(__('Kemana Labels'));

        return $resultPage;
    }
}
