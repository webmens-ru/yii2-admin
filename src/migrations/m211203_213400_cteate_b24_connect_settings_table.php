<?php

use yii\db\Migration;

class m211203_213400_cteate_b24_connect_settings_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%admin_b24_connect_settings%}}', [
            'name_id' => $this->string(255)->notNull(),
            'value' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
        ]);
        $this->addPrimaryKey('name_id', 'admin_b24_connect_settings', 'name_id');
        $this->batchInsert('admin_b24_connect_settings',
            [
                'name_id',
                'value',
                'name'
            ],
            [
                ['applicationId', 'local.000000000000000', 'applicationId'],
                ['applicationSecret', '00000000000000000', 'applicationSecret'],
                ['b24PortalName', '00.bitrix24.ru', 'b24PortalName'],
                ['b24PortalTable', 'admin_b24portal', 'b24PortalTable'],
                ['appId', '0', 'Id приложения'],
            ]

        );
    }

    public function down()
    {
        $this->dropTable('{{%admin_b24_connect_settings%}}');
    }
}