<?php

class m221006_115500_update_admin_placement extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_placement', 'entityTypeId', $this->string(50)->null());
    }

    public function down()
    {
    }
}
