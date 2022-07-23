<?php

use yii\db\Migration;

class m211225_103300_auth_assignment_add_data extends Migration
{
    public function up()
    {

        $this->batchInsert(
            'auth_assignment',
            [
                'item_name',
                'user_id',
                'created_at',
            ],
            [
                ['canAdmin', 1, null],
            ]
        );
    }
}
