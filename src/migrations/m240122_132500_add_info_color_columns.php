<?php

class m240122_132500_add_info_color_columns extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_grid_column', 'info', $this->string(255)->null());
        $this->addColumn('admin_grid_column', 'color', $this->string(32)->null());
    }

    public function down()
    {
    }
}