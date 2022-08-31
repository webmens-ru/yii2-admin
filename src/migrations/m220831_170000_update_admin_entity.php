<?php

use yii\db\Migration;

class m220831_170000_update_admin_entity extends Migration
{
    public function up()
    {
        $this->addColumn('admin_entity', 'params', 'text');
    }

    public function down()
    {

    }
}