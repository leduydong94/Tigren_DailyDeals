<?php

namespace Tigren\DailyDeals\Controller\Adminhtml\Deals;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tigren\DailyDeals\Model\DealsFactory;
use Magento\Ui\Component\MassAction\Filter;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory;

class Delete extends Action
{
    protected $_pageFactory;
    protected $_dealsFactory;
    protected $_filter;
    protected $_collectionFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        Filter $filter,
        DealsFactory $dealsFactory,
        CollectionFactory $collectionFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_dealsFactory = $dealsFactory;
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        // var_dump($collection); exit;
        $collectionSize = $collection->getSize();
        foreach ($collection as $item) {
            $item->delete();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.',$collectionSize));
        // die("abc");
        return $this->_redirect('daily/deals/index');
    }
}
