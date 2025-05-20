<?php

class m250516_162100_insert_biconnector_setting_types extends \yii\db\Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_biconnector_settings_type',
            [
                'code',
                'title',
            ],
            [
                [
                    'STRING',
                    'строка',
                ],
            ]
        );
    }

    public function down()
    {
    }
}
