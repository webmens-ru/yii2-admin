<?php

class m220816_150200_add_column_grid_column extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_grid_column', 'frozen', 'boolean');
        $this->addColumn('admin_grid_column', 'reordering', 'boolean');
        $this->addColumn('admin_grid_column', 'resizeble', 'boolean');
    }

    public function down()
    {
        $this->dropColumn('admin_grid_column', 'frozen');
        $this->dropColumn('admin_grid_column', 'reordering');
        $this->dropColumn('admin_grid_column', 'resizeble');
    }
}
