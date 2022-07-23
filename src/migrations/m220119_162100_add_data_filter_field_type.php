<?php

use yii\db\Migration;

class m220119_162100_add_data_filter_field_type extends Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_filter_field_type',
            [
                'id',
                'name'
            ],
            [
                [1, 'string'],
                [2, 'number'],
                [3, 'date'],
                [4, 'select'],
                [5, 'integer'],
                [6, 'multiple_select_dynamic'],
                [7, 'multiple_select'],
                [8, 'select_dynamic'],
            ]
        );
    }

    public function down()
    {
    }
}
