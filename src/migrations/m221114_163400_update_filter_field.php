<?php

class m221114_163400_update_filter_field extends \yii\db\Migration
{
    public function up()
    {
        $this->alterColumn('admin_filter_field', 'options', $this->text()->null());
    }

    public function down()
    {
    }
}
