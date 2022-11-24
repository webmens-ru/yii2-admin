<?php

class m221123_152600_add_synchronization_elements extends \yii\db\Migration
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
                ['Вспомогательные сущности', 0, '\wm\admin\models\synchronization\CrmStatus'],
                ['Направления', 0, '\wm\admin\models\synchronization\CrmCategory'],
            ]
        );
    }

    public function down()
    {
    }
}
