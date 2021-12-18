<?php

use yii\db\Migration;

class m211206_225100_cteate_filter_init extends Migration {

    public function up() {
        //admin_events_directory
        $this->createTable('{{%admin_filter%}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'entityCode' => $this->string(64)->notNull(),
            'isName' => $this->tinyInteger(1)->notNull(),
            'order' => $this->integer()->notNull(),
            'isBase' => $this->tinyInteger(1)->notNull(),
            'userId' => $this->integer()->null(),
            'parentId' => $this->integer()->null(),
        ]);
        $this->addForeignKey('admin_filter_ibfk_1', 'admin_filter', ['parentId'], 'admin_filter', ['id'], 'SET NULL', 'SET NULL');
        $this->addForeignKey('filter_fk0', 'admin_filter', ['entityCode'], 'admin_entity', ['code']);

        //$this->addPrimaryKey('name', 'admin_events_directory', 'name');
        //admin_robots_types
        $this->createTable('{{%admin_filter_field_type%}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);

        $this->createTable('{{%admin_filter_field%}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'entityCode' => $this->string(64)->notNull(),
            'typeId' => $this->integer()->notNull(),
            'order' => $this->integer()->notNull(),
            'code' => $this->string(32)->notNull(),
        ]);

        $this->addForeignKey('filter_field_fk0', 'admin_filter_field', ['entityCode'], 'admin_entity', ['code']);
        $this->addForeignKey('filter_field_fk1', 'admin_filter_field', ['typeId'], 'admin_filter_field_type', ['id']);

        $this->createTable('{{%admin_filter_field_options%}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'fieldId' => $this->integer()->notNull(),
            'order' => $this->integer()->notNull(),
            'value' => $this->string(255)->notNull(),
        ]);
        $this->addForeignKey('filter_field_options_fk0', 'admin_filter_field_options', ['fieldId'], 'admin_filter_field', ['id']);

        $this->createTable('{{%admin_filter_field_setting%}}', [
            'id' => $this->primaryKey(),
            'filterId' => $this->integer()->notNull(),
            'filterFieldId' => $this->integer()->notNull(),
            'value' => $this->json()->null(),
            'title' => $this->string(255)->notNull(),
            'order' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('filter_field_setting_fk0', 'admin_filter_field_setting', ['filterId'], 'admin_filter', ['id']);
        $this->addForeignKey('filter_field_setting_fk1', 'admin_filter_field_setting', ['filterFieldId'], 'admin_filter_field', ['id']);
    }

    public function down() {
        $this->dropTable('{{%admin_filter_field_setting%}}');
        $this->dropTable('{{%admin_filter_field_options%}}');
        $this->dropTable('{{%admin_filter_field%}}');
        $this->dropTable('{{%admin_filter_field_type%}}');
        $this->dropTable('{{%admin_filter%}}');
    }

}
