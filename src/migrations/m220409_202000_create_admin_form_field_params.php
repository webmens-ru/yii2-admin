<?php


class m220409_202000_create_admin_form_field_params extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_form_field_params%}}', [
            'id' => $this->integer(),
            'data' => $this->json(),
            'dataUrl' => $this->string(20),
            'minInputLength' => $this->integer(),
            'multiple' => $this->boolean(),
            'remodeMode' => $this->boolean(),
            'format' => $this->dateTime(),
            'isShowTime' => $this->boolean(),
            'PRIMARY KEY(id)',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_form_field_params%}}');
    }
}