<?php

use yii\db\Migration;

class m220322_125000_update_robots_options extends Migration
{
    public function up()
    {
        $this->dropPrimaryKey('code', 'admin_robots_options');
        $this->addPrimaryKey('code', 'admin_robots_options', ['property_name', 'robot_code', 'value']);
    }

    public function down()
    {
    }
}
