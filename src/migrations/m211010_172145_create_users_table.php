<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test}}`.
 */
class m211010_172145_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'b24_user_id' => $this->integer(),
            'name' => $this->string(),
            'last_name' => $this->string(),
            'access_token' => $this->string(),
            'date_expired' => $this->string(),
            'auth_key' => $this->string(),
            'b24AccessParams' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_users}}');
    }
}
