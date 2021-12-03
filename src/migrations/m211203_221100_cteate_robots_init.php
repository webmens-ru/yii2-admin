<?php

use yii\db\Migration;

class m211203_221100_cteate_robots_init extends Migration
{
    public function up()
    {
        //admin_robots
        $this->createTable('{{%admin_robots%}}', [
            'code' => $this->string(255)->notNull(),
            'handler' => $this->string(255)->notNull(),
            'auth_user_id' => $this->integer()->null(),
            'name' => $this->string(255)->null(),
            'use_subscription' => $this->tinyInteger(1)->null(),
            'use_placement' => $this->tinyInteger(1)->null(),
        ]);
        $this->addPrimaryKey('code', 'admin_robots', 'code');

        //admin_robots_types
        $this->createTable('{{%admin_robots_types%}}', [
            'id' => primaryKey(),
            'name' => $this->string(20)->notNull()->unique(),
            'is_static' => $this->tinyInteger(1)->notNull(),
            'is_options' => $this->tinyInteger(1)->notNull(),
        ]);
        $this->batchInsert('admin_robots_types',
            [
                'id',
                'name',
                'is_static',
                'is_options'
            ],
            [
                [1, 'bool', 0, 0],
                [2, 'date', 0, 0],
                [3, 'datetime', 0, 0],
                [4, 'double', 0, 0],
                [5, 'int', 0, 0],
                [6, 'select_dynamic', 0, 1],
                [7, 'select_static', 0, 1],
                [8, 'string', 0, 0],
                [9, 'text', 0, 0],
                [10, 'user', 0, 0],
            ]

        );

        //admin_robots_properties
        $this->createTable('{{%admin_robots_properties%}}', [
            'robot_code' => $this->string(255)->notNull(),
            'is_in' => $this->tinyInteger(1)->null(),
            'system_name' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
            'type_id' => $this->integer()->notNull(),
            'required' => $this->tinyInteger(1)->notNull(),
            'multiple' => $this->tinyInteger(1)->notNull(),
            'default' => $this->string(255)->null(),
            'sort' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('robot_code', 'admin_robots_properties', ['robot_code','system_name']);
        $this->addForeignKey('admin_robots_properties_fk0', 'admin_robots_properties', ['robot_code'], 'admin_robots', ['code'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('admin_robots_properties_fk1', 'admin_robots_properties', ['type_id'], 'admin_robots_types', ['id'], null, 'CASCADE');



        //admin_robots_options
        $this->createTable('{{%admin_robots_options%}}', [
            'property_name' => $this->string(255)->notNull(),
            'robot_code' => $this->string(255)->notNull(),
            'value' => $this->string(255)->notNull(),
            'name' => $this->string(255)->null(),
            'sort' => $this->integer()->null(),
        ]);
        $this->addPrimaryKey('code', 'admin_robots_options', ['property_name', 'robot_code']);
        $this->addForeignKey('admin_robots_options_fk0', 'admin_robots_options', ['robot_code','property_name'], 'admin_robots_properties', ['robot_code', 'system_name']);
    }

    public function down()
    {
        $this->dropTable('{{%admin_robots_options%}}');
        $this->dropTable('{{%admin_robots_properties%}}');
        $this->dropTable('{{%admin_robots_types%}}');
        $this->dropTable('{{%admin_robots%}}');
    }
}