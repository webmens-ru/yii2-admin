<?php

class m230314_074700_add_item_admin_grid_column_type extends \yii\db\Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_grid_column_type',
            [
                'code',
                'title',
            ],
            [
                ['image', 'Картинка'],
            ]
        );
    }

    public function down()
    {
    }
}
