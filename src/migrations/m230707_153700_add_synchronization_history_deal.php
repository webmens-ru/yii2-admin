<?php

class m230707_153700_add_synchronization_history_deal extends \yii\db\Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_synchronization',
            [
                'title',
                'active',
                'modelClassName'
            ],
            [
                ['История перемещения по стадиям Сделок', 0, '\wm\admin\models\synchronization\HistoryDeal'],
            ]
        );
    }

    public function down()
    {
    }
}
