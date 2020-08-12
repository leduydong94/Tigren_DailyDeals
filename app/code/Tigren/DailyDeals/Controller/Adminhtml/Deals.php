<?php

namespace Tigren\DailyDeals\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Tigren\DailyDeals\Model\DealsFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;



class Deals extends Action
{
    protected $_coreRegistry;
    protected $_resultPageFactory;
    protected $_dealsFactory;
    protected $_product;
    protected $_productFactory;
    protected $_config;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        DealsFactory $dealsFactory,
        ProductCollection $product,
        ProductFactory $productFactory,
        ScopeConfigInterface $config
    )
    {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_dealsFactory = $dealsFactory;
        $this->_product = $product;
        $this->_productFactory = $productFactory;
        $this->_config = $config;
    }

    /**
     * @return \Magento\Framework\App\Response\RedirectInterface
     */

    public function execute()
    {

    }

    protected function _isAllowed()
    {
        $configValue = $this->_config->getValue('dailydeals_section_id/general/enable');
        if ($configValue != 0) {
            return true;
        } else {
            return false;
        }
    }
}
