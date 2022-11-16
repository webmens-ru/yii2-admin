<?php

class m220409_202000_create_admin_form extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_form%}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'mode' => $this->string(5)->notNull(),
            'canToggleMode' => $this->boolean()->notNull(),
            'action' => $this->json(),
            'params' => $this->json(),
            'buttons' => $this->json(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_form%}}');
    }
}
