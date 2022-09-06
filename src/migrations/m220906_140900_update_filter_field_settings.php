<?php

use yii\db\Migration;

class m220906_140900_update_filter_field_settings extends Migration
{
    public function up()
    {
        $this->dropColumn('admin_filter_field_setting', 'title');    
    }

    public function down()
    {

    }
}