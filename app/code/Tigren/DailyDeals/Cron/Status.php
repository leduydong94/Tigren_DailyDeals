<?php
namespace Tigren\DailyDeals\Cron;

use Tigren\DailyDeals\Model\ResourceModel\Deals\CollectionFactory as DealCollection;
use Tigren\DailyDeals\Model\DealsFactory;

class Status
{
    protected $dealCollection;
    protected $dealFactory;

    public function __construct(
        DealCollection $dealCollection,
        DealsFactory $dealFactory
    )
    {
        $this->dealCollection = $dealCollection;
        $this->dealFactory = $dealFactory;
    }

    public function execute()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = date('Y-m-d H:i:s');
        $deals = $this->dealCollection->create();

        foreach ($deals as $item) {
           $itemId = $item->getData('id');
           $endTime = $item->getData('end_date_time');

           $deal = $this->dealFactory->create()->load($itemId);
           if ($now > $endTime) {
               $deal->setData('status', 2);
               $deal->save();
           }
       }
    }
}
