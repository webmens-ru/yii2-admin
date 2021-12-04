<?php

use yii\db\Migration;

class m211204_120900_cteate_agents_init extends Migration
{
    public function up()
    {
        //admin_agents
        $this->createTable('{{%admin_agents%}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'class' => $this->string(255)->notNull(),
            'method' => $this->string(64)->notNull(),
            'params' => $this->text()->notNull(),
            'date_run' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
            'period' => $this->integer()->notNull(),
            'status_id' => $this->integer()->notNull(),
        ]);
        $this->batchInsert('admin_agents',
            [
                'id, name', 'class', 'method', 'params', 'date_run', 'period', 'status_id'
            ],
            [
                [1, 'События', 'wm\\admin\\models\\settings\\events\\Events', 'getOflineEventsHendlersRun', '-', '2021-01-01 00:00:00', 55, 1],
            ]

        );
    }

    public function down()
    {
        $this->dropTable('{{%admin_agents%}}');
    }
}