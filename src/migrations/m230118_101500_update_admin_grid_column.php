<?php

class m230118_101500_update_admin_grid_column extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_grid_column', 'editable', $this->boolean()->defaultValue(0));
        $this->addColumn('admin_grid_column', 'editor', $this->json()->null());
    }

    public function down()
    {
    }
}
