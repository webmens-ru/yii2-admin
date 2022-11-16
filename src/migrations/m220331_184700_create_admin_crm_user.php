<?php

class m220331_184700_create_admin_crm_user extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_crm_user%}}', [
            'ID' => $this->integer(11),
            'ACTIVE' => $this->boolean(6),
            'DATE_REGISTER' => $this->string(255),
            'IS_ONLINE' => $this->string(255),
            'LAST_ACTIVITY_DATE' => $this->json(),
            'LAST_NAME' => $this->string(255),
            'NAME' => $this->string(255),
            'PERSONAL_BIRTHDAY' => $this->string(255),
            'PERSONAL_CITY' => $this->string(255),
            'PERSONAL_COUNTRY' => $this->string(255),
            'PERSONAL_GENDER' => $this->string(255),
            'PERSONAL_PHOTO' => $this->string(255),
            'PERSONAL_PROFESSION' => $this->string(255),
            'PERSONAL_STATE' => $this->string(255),
            'SECOND_NAME' => $this->string(255),
            'TIMESTAMP_X' => $this->json(),
            'TIME_ZONE' => $this->string(255),
            'TIME_ZONE_OFFSET' => $this->string(255),
            'TITLE' => $this->string(255),
            'UF_DEPARTMENT' => $this->json(),
            'UF_EMPLOYMENT_DATE' => $this->string(255),
            'UF_INTERESTS' => $this->string(255),
            'UF_PHONE_INNER' => $this->string(255),
            'UF_SKILLS' => $this->string(255),
            'UF_TIMEMAN' => $this->string(255),
            'USER_TYPE' => $this->string(255),
            'WORK_CITY' => $this->string(255),
            'WORK_COUNTRY' => $this->string(255),
            'WORK_POSITION' => $this->string(255),
            'WORK_STATE' => $this->string(255),
            'XML_ID' => $this->string(255),
            'PRIMARY KEY(ID)',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_crm_user%}}');
    }
}
