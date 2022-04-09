<?php


class m220409_202200_create_admin_form_fields extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_form_fields%}}', [
            'id' => $this->integer(),
            'fieldsetId' => $this->integer()->notNull(),
            'type' => $this->string(20)->notNull(),
            'name' => $this->string(255)->notNull(),
            'label' => $this->string(255)->notNull(),
            'readOnly' => $this->boolean(),
            'value' => $this->integer(),
            'fieldParamsId' => $this->integer(),
            'visible' => $this->boolean(),
            'order' => $this->integer(),
            'PRIMARY KEY(id)',
        ]);

        $this->addForeignKey(
            'fieldsetId',
            'admin_form_fields',
            'fieldsetId',
            'admin_form_fieldset',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fieldParamsId',
            'admin_form_fields',
            'fieldParamsId',
            'admin_form_field_params',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%admin_form_fields%}}');

        $this->dropForeignKey(
            'fieldsetId',
            'admin_form_fieldset'
        );

        $this->dropForeignKey(
            'fieldParamsId',
            'admin_form_field_params'
        );
    }
}