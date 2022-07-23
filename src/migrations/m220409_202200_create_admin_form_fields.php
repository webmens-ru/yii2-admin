<?php

class m220409_202200_create_admin_form_fields extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_form_fields%}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'type' => $this->string(20)->notNull(),
            'label' => $this->string(255)->notNull(),
            'fieldParams' => $this->json(),
            'formId' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'formId',
            'admin_form_fields',
            'formId',
            'admin_form',
            'id',
            'NO ACTION'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'formId',
            'admin_form'
        );

        $this->dropTable('{{%admin_form_fields%}}');
    }
}
