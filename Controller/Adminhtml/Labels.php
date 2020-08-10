<?php
/**
 * Kemana Pvt Ltd.
 *
 * @category    Kemana
 * @package     Kemana_Labelmanager
 * @author      Kemana Team <contact@kemana.com>
 * @copyright   Copyright (c) 2016 Kemana Pvt Ltd.
 */
namespace Kemana\Labelmanager\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Kemana\Labelmanager\Model\LabelsFactory;
use Kemana\Labelmanager\Model\OptionsFactory;

/**
 * Class Labels
 *
 * @package Kemana\Labelmanager\Controller\Adminhtml
 */
abstract class Labels extends Action
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var LabelsFactory
     */
    protected $_labelsFactory;

    /**
     * @var OptionsFactory
     */
    protected $_optionsFactory;

    /**
     * Labels constructor.
     * @param Action\Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param LabelsFactory $labelsFactory
     * @param OptionsFactory $optionsFactory
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        LabelsFactory $labelsFactory,
        OptionsFactory $optionsFactory
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_labelsFactory = $labelsFactory;
        $this->_optionsFactory = $optionsFactory;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Kemana_Labelmanager::labels');
    }
}
