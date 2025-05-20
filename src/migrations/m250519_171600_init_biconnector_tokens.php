<?php

use yii\db\Migration;

class m250519_171600_init_biconnector_tokens extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%admin_biconnector_tokens}}', [
            'id' => $this->primaryKey(),
            'token' => $this->string(32)->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%admin_biconnector_tokens}}');
    }
}