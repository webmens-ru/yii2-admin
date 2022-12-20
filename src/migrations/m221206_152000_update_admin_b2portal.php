<?php

class m221206_152000_update_admin_b2portal extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_b24portal', 'applicationToken', $this->string(255)->null());
    }

    public function down()
    {
    }
}
