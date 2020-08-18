<?php
namespace Tigren\DailyDeals\Setup;

use Magento\Framework\DB\Ddl\Table;
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
                        'product_sku',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Product SKU'
                    )
                    ->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        [],
                        'Status'
                    )
                    ->addColumn(
                        'start_date_time',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        null,
                        [],
                        'Start Date Time'
                    )->addColumn(
                        'end_date_time',
                        \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        null,
                        [],
                        'End Date Time'
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
                    )->addColumn(
                        'store_view',
                        Table::TYPE_INTEGER,
                        1,
                        [],
                        'Store View'
                    )->setComment('Daily Deals Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('tigren_daily_deals'),
                    $setup->getIdxName(
                        $installer->getTable('tigren_daily_deals'),
                        ['product_sku','status','start_date_time','end_date_time','deal_price','deal_qty', 'store_view'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['product_sku','status','start_date_time','end_date_time','deal_price','deal_qty', 'store_view'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
        }

        $installer->endSetup();
    }
}
