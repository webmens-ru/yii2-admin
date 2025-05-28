<?php

use yii\db\Migration;

class m250528_084400_add_agent_clear_old_logs extends Migration
{
    public function up()
    {

        $this->batchInsert(
            'admin_agents',
            [
                'name',
                'class',
                'method',
                'params',
                'date_run',
                'status_id',
                'minuteTypeId',
                'minuteProps',
                'hourTypeId',
                'hourProps',
                'dayTypeId',
                'dayProps',
                'monthTypeId',
                'monthProps',
                'finishTypeId',
                'finishProps'
            ],
            [
                [
                    'Очистка старых логов',
                    'wm\\admin\\models\\Log',
                    'clearOldLogs',
                    '-',
                    '2021-01-01 00:00:00',
                    1,
                    3,
                    1,
                    3,
                    1,
                    1,
                    '',
                    1,
                    '',
                    1,
                    ''
                ],
            ]
        );
    }

    public function down()
    {

    }
}
