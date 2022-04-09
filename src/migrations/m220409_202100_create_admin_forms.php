<?php


class m220409_202100_create_admin_forms extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_forms%}}', [
            'id' => $this->integer(11),
            'mode' => $this->string(5)->notNull(),
            'title' => $this->string(255)->notNull(),
            'canToggleMode' => $this->boolean()->notNull(),
            'action' => $this->string(255),
            'params' => $this->json(),
            'buttons' => $this->json(),
            'PRIMARY KEY(id)',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_forms%}}');
    }
}