<?php

class m230730_092700_add_synchronization_requisite extends \yii\db\Migration
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
                ['Реквизиты', 0, '\wm\admin\models\synchronization\Requisite'],
            ]
        );
    }

    public function down()
    {
    }
}
