<?php

use yii\db\Migration;

class m211203_212900_cteate_settings_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%admin_settings%}}', [
            'name_id' => $this->string(255)->notNull(),
            'value' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'PRIMARY KEY(name_id)',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_settings%}}');
    }
}