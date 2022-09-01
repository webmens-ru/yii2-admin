<?php

use yii\db\Migration;

class m220901_112000_update_filter_field extends Migration
{
    public function up()
    {
        $this->addColumn('admin_filter_field', 'filtrationType', $this->string(6)->defaultValue('server'));
        $this->addColumn('admin_filter_field', 'options', $this->text());

    }

    public function down()
    {

    }
}