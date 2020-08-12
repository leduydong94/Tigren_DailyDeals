<?php
namespace Tigren\DailyDeals\Controller\Adminhtml\Deals;
use Tigren\DailyDeals\Controller\Adminhtml\Deals;

class Edit extends Deals
{
    /**
     * @return void
     */
    public function execute()
    {
//         die(__METHOD__);
        $postId = $this->getRequest()->getParam('id');

        $model = $this->_dealsFactory->create();

        if ($postId) {
            $model->load($postId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This news no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        // Restore previously entered form data from session
        $data = $this->_session->getNewsData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('tigren_daily_deals', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Tigren_DailyDeals::tigren');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Deals'));

        return $resultPage;
    }
}
