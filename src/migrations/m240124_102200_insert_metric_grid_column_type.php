<?php

class m240124_102200_insert_metric_grid_column_type extends \yii\db\Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_grid_column_type',
            [
                'code',
                'title'
            ],
            [
                ['metric', 'Число с произвольным форматированием'],
            ]
        );
  }

    public function down()
    {
    }
}
