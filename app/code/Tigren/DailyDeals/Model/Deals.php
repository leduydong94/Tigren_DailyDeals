<?php

namespace Tigren\DailyDeals\Model;

use Magento\Framework\Model\AbstractModel;

class Deals extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Tigren\DailyDeals\Model\ResourceModel\Deals');
    }
}
