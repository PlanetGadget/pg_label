<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Block\Adminhtml\File;

class Manage extends \Magento\Backend\Block\Template
{
    /**
     * @var \Kemana\Labelmanager\Model\DataFile
     */
    protected $fileModel;

    /**
     * Manage constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Kemana\Labelmanager\Model\DataFile $fileModel
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Kemana\Labelmanager\Model\DataFile $fileModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->fileModel = $fileModel;
    }

    /**
     * Setting Template file
     */
    protected function _construct()
    {
        $this->setTemplate('Kemana_Labelmanager::file/manage.phtml');
        parent::_construct();
    }

    /**
     * @param int $websiteId
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getGenerateFileButtonsHtml($websiteId)
    {
        return $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
            ->setData(
                [
                    'label' => __('Generate Label Item Data'),
                    'onclick' => 'manageFile.generateFile(this, \'' . $websiteId . '\')',
                    'class' => 'add'
                ]
            )
            ->toHtml();
    }

    /**
     * @return File
     */
    public function getLabels()
    {
        return $this->fileModel->getLabelsFile();
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->fileModel->getLabelDataFiles();
    }

    /**
     * @return string
     */
    public function generateFileUrl()
    {
        return $this->getUrl('*/*/generate');
    }
}
