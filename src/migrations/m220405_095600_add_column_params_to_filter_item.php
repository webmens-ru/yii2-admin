<?php


class m220405_095600_add_column_params_to_filter_item extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('admin_filter_field', 'params', 'text');
    }

    public function down()
    {
        $this->dropColumn('admin_filter_field', 'params');
    }
}