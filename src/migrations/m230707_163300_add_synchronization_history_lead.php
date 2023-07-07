<?php

class m230707_163300_add_synchronization_history_lead extends \yii\db\Migration
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
                ['История перемещения по стадиям Лидов', 0, '\wm\admin\models\synchronization\HistoryLead'],
            ]
        );
    }

    public function down()
    {
    }
}
