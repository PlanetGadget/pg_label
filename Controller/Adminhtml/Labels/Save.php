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

use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\TestFramework\Event\Magento;
use Kemana\Labelmanager\Controller\Adminhtml\Labels;
//use Magento\Framework\UrlInterface;
//use Magento\Framework\App\Filesystem\DirectoryList;
use Kemana\Labelmanager\Model\LabelsFactory;
use Kemana\Labelmanager\Model\OptionsFactory;
//use Symfony\Component\Config\Definition\Exception\Exception;
use Kemana\Labelmanager\Model\UploadFactory;

/**
 * Class Save
 * @package Kemana\Labelmanager\Controller\Adminhtml\Labels
 */
class Save extends Labels
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var LabelsFactory
     */
    protected $labelsFactory;

    /**
     * @var OptionsFactory
     */
    protected $optionsFactory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploadFactory;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * Save constructor     *
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Registry $registry,
        PageFactory $resultPageFactory,
        LabelsFactory $labelsFactory,
        OptionsFactory $optionsFactory,
        UploadFactory $uploadFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->labelsFactory = $labelsFactory;
        $this->optionsFactory = $optionsFactory;
        $this->filesystem = $filesystem;
        $this->uploadFactory = $uploadFactory;

        parent::__construct($context, $registry, $resultPageFactory, $labelsFactory, $optionsFactory);
    }

    /**
     * Save labels
     * @return \Magento\Framework\View\Result\Page|void
     */
    public function execute()
    {
        $isPost = $this->getRequest()->getPost();

        if ($isPost) {
            $labelModel = $this->labelsFactory->create();
            $optionModel = $this->optionsFactory->create();
            $uploadModel = $this->uploadFactory->create();
            $labelId = $this->getRequest()->getParam('label_id');

            if ($labelId) {
                $labelModel->load($labelId);
            }

            $formData = $this->getRequest()->getParams();

            if (isset($formData['prod_labelimg']) && !empty($formData['prod_labelimg'])) {
                $productLabelImage = $formData['prod_labelimg'];
            } elseif (isset($formData['labels']['post_image_product']) &&
                !empty($formData['labels']['post_image_product'])) {
                $productLabelImage = $formData['labels']['post_image_product'];
            } else {
                $productLabelImage = '';
            }
            if ($productLabelImage != '') {
                $productLabelImage = $uploadModel->resizeProdImage($productLabelImage, 200, 200);
            }

            ($formData['labels']['sort_order']=="" || $formData['labels']['sort_order']==0) ?
                $formData['labels']['sort_order']=1 : $formData['labels']['sort_order']=$formData['labels']['sort_order'];

            $formData['labels']['image_product'] = $productLabelImage;
            $formData['labels']['image_category'] = $productLabelImage;

            $labelModel->setData($formData);
            unset($formData['prod_labelimg']);

            /*if only one store exists set default*/
            if (!isset($formData['labels']['stores'])) {
                $formData['labels']['stores'] = 1;
            } else {
                $formData['labels']['stores'] = implode(',', $formData['labels']['stores']);
            }

            try {
                // Save labels
                $labelModel->save();

                $formData['labels']['label_id'] = $labelModel->getLabelId();
                $optionModel->setData($formData['labels']);

                $optionModel->save();

                // Display success message
                $this->messageManager->addSuccess(__('Label settings has been saved.'));

                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['label_id' => $labelModel->getLabelId(), '_current' => true]);
                    return;
                }

                // Go to grid page
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }

            $this->_getSession()->setFormData($formData);
            $this->_redirect('*/*/edit', ['label_id' => $labelId]);
        }
    }
}
