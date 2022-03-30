<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test}}`.
 */
class m220330_155700_create_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admin_grid_action}}', [
            'id' => $this->primaryKey(),
            'entityCode' => $this->string(64)->notNull(),
            'title' => $this->string()->notNull(),
            'handler' => $this->string()->notNull(),
            'params' => $this->string(),

        ]);
        $this->addForeignKey('grid_action_fk0', 'admin_grid_action', ['entityCode'], 'admin_entity', ['code']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_grid_action}}');
    }
}
