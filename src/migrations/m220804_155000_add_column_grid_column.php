<?php

class m220804_155000_add_column_grid_column extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_grid_column', 'type', 'string(32)');
    }

    public function down()
    {
        $this->dropColumn('admin_grid_column', 'type');
    }
}
