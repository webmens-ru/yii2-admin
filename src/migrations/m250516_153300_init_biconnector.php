<?php

class m250516_153300_init_biconnector extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_biconnector%}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'logo' => $this->text()->notNull(),
            'description' => $this->string(255)->notNull(),
            'urlCheck' => $this->string(255)->notNull(),
            'urlTableList' => $this->string(255)->notNull(),
            'urlTableDescription' => $this->string(255)->notNull(),
            'urlData' => $this->string(255)->notNull(),
            'sort' => $this->integer()->notNull(),
            'bx24Id' => $this->integer(),
            'isSystem' => $this->tinyInteger(1)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_biconnector%}}');
    }
}
