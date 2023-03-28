<?php

class m230323_125200_edite_links extends \yii\db\Migration
{
    public function up()
    {
        $this->dropForeignKey('filter_fk0', 'admin_filter');
        $this->addForeignKey('admin_filter_fk0', 'admin_filter', ['entityCode'], 'admin_entity', ['code'], 'RESTRICT', 'CASCADE');
        $this->dropForeignKey('filter_field_fk0', 'admin_filter_field');
        $this->addForeignKey('admin_filter_field_fk0', 'admin_filter_field', ['entityCode'], 'admin_entity', ['code'], 'RESTRICT', 'CASCADE');
        $this->dropForeignKey('grid_column_fk0', 'admin_grid_column');
        $this->addForeignKey('admin_grid_column_fk0', 'admin_grid_column', ['entityCode'], 'admin_entity', ['code'], 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
    }
}
