<?php

class m221014_114000_update_admin_events extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_events', 'entityTypeId', $this->string(50)->null());
    }

    public function down()
    {
    }
}
