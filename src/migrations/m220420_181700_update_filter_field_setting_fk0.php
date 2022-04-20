<?php


class m220420_181700_update_filter_field_setting_fk0 extends \yii\db\Migration
{
    public function up()
    {
        $this->dropForeignKey('filter_field_setting_fk0', 'admin_filter_field_setting');
        $this->addForeignKey('filter_field_setting_fk0', 'admin_filter_field_setting', ['filterId'], 'admin_filter', ['id'], 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        
    }
}