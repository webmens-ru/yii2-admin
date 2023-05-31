<?php

class m230526_084000_add_synchronization_task_elapsed_item extends \yii\db\Migration
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
                ['Затраченное время в задачах', 0, '\wm\admin\models\synchronization\TasklElapsedItem'],
            ]
        );
    }

    public function down()
    {
    }
}
