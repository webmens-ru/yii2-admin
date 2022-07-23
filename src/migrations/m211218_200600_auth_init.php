<?php

use yii\db\Migration;

class m211218_200600_auth_init extends Migration
{
    public function up()
    {

        $this->batchInsert(
            'auth_item',
            [
                'name',
                'type',
                'description',
                'rule_name',
                'data',
                'created_at',
                'updated_at',
            ],
            [
                ['admin', 1, 'Администратор', null, null, null, null],
                ['canAdmin', 2, 'Право входа в админку', null, null, null, null],
            ]
        );

        $this->batchInsert(
            'auth_item_child',
            [
                'parent',
                'child',
            ],
            [
                ['admin', 'canAdmin'],
            ]
        );
    }
}
