<?php

use yii\db\Migration;

class m220331_075800_add_agent_crm_status_synchronization extends Migration
{

    public function up()
    {
        $this->batchInsert('admin_agents',
            [
                'name',
                'class',
                'method',
                'params',
                'date_run',
                'period',
                'status_id'
            ],
            [
                ['Обновление справочников', 'wm\admin\models\CrmStatus', 'synchronization', '-', '2021-01-00 00:00:00', 86395, 1],
            ]
        );
    }

    public function down()
    {
    }

}
