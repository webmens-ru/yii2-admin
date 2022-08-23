<?php

class m220816_150200_add_column_grid_column extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_grid_column', 'frozen', $this->boolean()->defaultValue(0));
        $this->addColumn('admin_grid_column', 'reordering', $this->boolean()->defaultValue(1));
        $this->addColumn('admin_grid_column', 'resizeble', $this->boolean()->defaultValue(1));
        $this->addColumn('admin_grid_column', 'sortable', $this->boolean()->defaultValue(1));

    }

    public function down()
    {
    }
}
