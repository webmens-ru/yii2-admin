<?php

class m220419_152600_delete_agent extends \yii\db\Migration
{
    public function up()
    {
        $this->delete('admin_agents', ['method' => 'getOflineEventsHendlersRun']);
    }

    public function down()
    {
    }
}
