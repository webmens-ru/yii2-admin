<?php

class m250516_160700_init_biconnector_settings extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_biconnector_settings%}}', [
            'biconnectorId' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
            'type' => $this->string(255)->notNull(),
            'code' => $this->string(255)->notNull(),
        ]);

        $this->addPrimaryKey('code', 'admin_biconnector_settings', ['biconnectorId','code']);
        $this->addForeignKey('admin_biconnector_settings_fk0', 'admin_biconnector_settings', ['biconnectorId'], 'admin_biconnector', ['id'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('admin_biconnector_settings_fk1', 'admin_biconnector_settings', ['type'], 'admin_biconnector_settings_type', ['code'], 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{%admin_biconnector_settings%}}');
    }
}
