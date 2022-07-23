<?php

class m220329_200600_create_admin_crm_status extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_crm_status%}}', [
            'ID' => $this->string(32),
            'CATEGORY_ID' => $this->string(255),
            'COLOR' => $this->string(255),
            'ENTITY_ID' => $this->string(255),
            'NAME' => $this->string(255),
            'NAME_INIT' => $this->string(255),
            'SEMANTICS' => $this->string(255),
            'SORT' => $this->string(255),
            'STATUS_ID' => $this->string(255),
            'SYSTEM' => $this->string(255),
            'PRIMARY KEY(ID)',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_crm_status%}}');
    }
}
