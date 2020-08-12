<?php
namespace Tigren\DailyDeals\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Deals extends AbstractDb
{
    protected function _construct()
    {
        // sample_hello là tên bảng , id là khóa chính primary của bảng
        $this->_init('tigren_daily_deals', 'id');
    }
}
