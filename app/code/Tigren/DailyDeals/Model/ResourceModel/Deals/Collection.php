<?php
namespace Tigren\DailyDeals\Model\ResourceModel\Deals;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected function _construct()
    {
        $this->_init(
            'Tigren\DailyDeals\Model\Deals',
            'Tigren\DailyDeals\Model\ResourceModel\Deals'
        );
    }
}
