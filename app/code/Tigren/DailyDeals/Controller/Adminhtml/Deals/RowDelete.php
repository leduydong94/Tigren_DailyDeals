<?php
namespace Tigren\DailyDeals\Controller\Adminhtml\Deals;

use Exception;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Tigren\DailyDeals\Model\Deals;
use Tigren\DailyDeals\Model\DealsFactory;
use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory;

class RowDelete extends Action
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
        $id = $this->getRequest()->getParam('id');
//        die($id); exit;
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
//            $title = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create(Deals::class);
                $model->load($id);
//                $title = $model->getTitle();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The deals has been deleted.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a news to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
