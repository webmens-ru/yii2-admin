<?php

use yii\db\Migration;

class m220831_170000_add_admin_entity extends Migration
{
    public function up()
    {
        $this->addColumn('admin_entity', 'params', $this->text()->null());
    }

    public function down()
    {

    }
}