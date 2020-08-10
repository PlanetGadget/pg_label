<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Controller\Adminhtml\Labeldata;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

class Generate extends Action
{
    /**
     * @var \Kemana\Labelmanager\Model\DataFile
     */
    private $fileModel;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * Generate constructor.
     * @param Action\Context $context
     * @param \Kemana\Labelmanager\Model\DataFile $fileModel
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        Action\Context $context,
        \Kemana\Labelmanager\Model\DataFile $fileModel,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->fileModel = $fileModel;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        try {
            $data = $this->getRequest()->getPostValue('website');
            if ($data) {
                $fileData = $this->fileModel->prepareFile($data);
                $result = [
                    'message' => '',
                    'file_exist' => $fileData['file_exists'],
                    'labels' => $fileData['labels'],
                    'labelProducts' => $fileData['labelProducts']
                ];
            } else {
                $result = ['error' => 'Invalid parameter'];
            }
        } catch (\Exception $exception) {
            $result = ['error' => $exception->getMessage()];
        }

        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}
