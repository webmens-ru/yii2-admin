<?php

class m230831_062000_update_admin_menu_item extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_menu_item', 'authItem', $this->string(255)->null());
    }

    public function down()
    {
    }
}
