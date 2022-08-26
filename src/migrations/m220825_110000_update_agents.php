<?php

use yii\db\Migration;

class m220825_110000_update_agents extends Migration
{
    public function up()
    {
        //admin_agents
        $this->alterColumn('admin_agents', 'period', 'int');
        $this->addColumn('admin_agents', 'minuteTypeId', 'int');
        $this->addColumn('admin_agents', 'minuteProps', 'string');
        $this->addColumn('admin_agents', 'hourTypeId', 'int');
        $this->addColumn('admin_agents', 'hourProps', 'string');
        $this->addColumn('admin_agents', 'dayTypeId', 'int');
        $this->addColumn('admin_agents', 'dayProps', 'string');
        $this->addColumn('admin_agents', 'monthTypeId', 'int');
        $this->addColumn('admin_agents', 'monthProps', 'string');
        $this->addColumn('admin_agents', 'finishTypeId', 'int');
        $this->addColumn('admin_agents', 'finishProps', 'string');
    }

    public function down()
    {

    }
}