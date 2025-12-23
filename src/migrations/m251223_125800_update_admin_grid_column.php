<?php

class m251223_125800_update_admin_grid_column extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_grid_column', 'editorProps', $this->json()->null());
    }

    public function down()
    {
    }
}