<?php

use yii\db\Migration;

class m211206_215000_cteate_entity_table extends Migration
{
    public function up()
    {
        //admin_agents
        $this->createTable('{{%admin_entity%}}', [
            'code' => $this->string(64)->notNull(),
            'name' => $this->string(255)->notNull(),

        ]);
        $this->addPrimaryKey('code', 'admin_entity', 'code');
    }

    public function down()
    {
        $this->dropTable('{{%admin_entity%}}');
    }
}