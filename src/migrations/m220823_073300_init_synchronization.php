<?php

class m220823_073300_init_synchronization extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_synchronization%}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'active' => $this->tinyInteger(1)->notNull(),
            'modelClassName' => $this->string(255)->notNull(),
        ]);

        $this->createTable('{{%admin_synchronization_field%}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(48)->notNull(),
            'synchronizationEntityId' => $this->integer()->notNull(),
            'title' => $this->string(128)->notNull(),
            'noDelete' => $this->tinyInteger(1)->notNull(),

        ]);
        $this->createIndex(
            'name',
            '{{%admin_synchronization_field%}}',
            ['name', 'synchronizationEntityId'],
            true
        );
        $this->addForeignKey('admin_synchronization_field_fk0', 'admin_synchronization_field', ['synchronizationEntityId'], 'admin_synchronization', ['id'], 'CASCADE', 'CASCADE');
        $this->insert(
            'admin_synchronization',
            [
                'title' => 'Лиды',
                'active' => 0,
                'modelClassName' => '\wm\admin\models\synchronization\Lead',
            ]
        );
        $this->insert(
            'admin_synchronization',
            [
                'title' => 'Сделки',
                'active' => 0,
                'modelClassName' => '\wm\admin\models\synchronization\Deal',
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%admin_synchronization_field%}}');
        $this->dropTable('{{%admin_synchronization%}}');
    }
}
