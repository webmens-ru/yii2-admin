<?php

class m220822_140500_update_admin_grid_action extends \yii\db\Migration
{
    public function up()
    {
        $this->alterColumn('admin_grid_action', 'params', 'text');
    }

    public function down()
    {
    }
}
