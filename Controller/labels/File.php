<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) Kemana Pvt Ltd.
 */

namespace Kemana\Labelmanager\Controller\labels;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Kemana\Labelmanager\ViewModel\FileManager;

class File extends Action
{
    /**
     * @var FileManager
     */
    private $fileManager;

    private $resultJsonFactory;

    /**
     * File constructor.
     * @param Context $context
     * @param FileManager $fileManager
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        FileManager $fileManager,
        JsonFactory $resultJsonFactory
    ) {
        $this->fileManager = $fileManager;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $fileUrl = $this->fileManager->getLabelDataFile();
        return $this->resultJsonFactory->create()->setData($fileUrl);
    }
}
