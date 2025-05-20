<?php

class m250519_152300_init_biconnector_tables extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_biconnector_tables%}}',
            [
            'name' => $this->string(64)->notNull(),
            'title' => $this->string(255)->notNull(),
        ]);

        $this->addPrimaryKey('name', 'admin_biconnector_tables', ['name']);
    }

    public function down()
    {
        $this->dropTable('{{%admin_biconnector_tables%}}');
    }
}
