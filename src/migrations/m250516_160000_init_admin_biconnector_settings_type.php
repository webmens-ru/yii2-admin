<?php

class m250516_160000_init_admin_biconnector_settings_type extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_biconnector_settings_type%}}', [
            'code' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
        ]);

        $this->addPrimaryKey('code', 'admin_biconnector_settings_type', ['code']);
    }

    public function down()
    {
        $this->dropTable('{{%admin_biconnector_settings_type%}}');
    }
}
