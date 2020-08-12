<?php

namespace Tigren\DailyDeals\Controller\Adminhtml\Deals;

use Tigren\DailyDeals\Controller\Adminhtml\Deals;

class Index extends Deals
{

    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Deals Manager')));

        return $resultPage;
    }
}
