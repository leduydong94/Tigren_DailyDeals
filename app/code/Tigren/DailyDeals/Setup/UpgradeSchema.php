<?php
namespace Tigren\DailyDeals\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();

        if(version_compare($context->getVersion(), '1.0.1', '<')) {
            if (!$installer->tableExists('tigren_daily_deals')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('tigren_daily_deals')
                )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'ID'
                    )
                    ->addColumn(
                        'product_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Product ID'
                    )
                    ->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        [],
                        'Status'
                    )
                    ->addColumn(
                        'start_date',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        null,
                        [],
                        'Start Date'
                    )
                    ->addColumn(
                        'start_time',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Start Time'
                    )->addColumn(
                        'end_date',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        null,
                        [],
                        'End Date'
                    )->addColumn(
                        'end_time',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'End Time'
                    )->addColumn(
                        'deal_price',
                        \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                        10,
                        [],
                        'Deal Price'
                    )->addColumn(
                        'deal_qty',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        4,
                        [],
                        'Deal Quantity'
                    )->setComment('Daily Deals Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('tigren_daily_deals'),
                    $setup->getIdxName(
                        $installer->getTable('tigren_daily_deals'),
                        ['product_name','status','start_date','start_time','end_date','end_time','deal_price','deal_qty'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['product_name','status','start_date','start_time','end_date','end_time','deal_price','deal_qty'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
        }

        $installer->endSetup();
    }
}
