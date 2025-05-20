<?php

class m250516_171500_insert_biconnector_settings extends \yii\db\Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_biconnector_settings',
            [
                'biconnectorId',
                'name',
                'type',
                'code',
            ],
            [
                [
                    1,
                    'Токен',
                    'STRING',
                    'token',
                ],
            ]
        );
    }

    public function down()
    {
    }
}
