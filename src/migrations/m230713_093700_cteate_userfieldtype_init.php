<?php

use yii\db\Migration;

class m230713_093700_cteate_userfieldtype_init extends Migration
{
    public function up()
    {
        //admin_agents
        $this->createTable('{{%admin_userfieldtype%}}', [
            'id' => $this->primaryKey(),
            'userTypeId' => $this->string(255)->notNull(),
            'handler' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->string(255),
            'optionsHeight' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_userfieldtype%}}');
    }
}
