<?php

use yii\db\Migration;

class m211204_225700_cteate_dg_table extends Migration
{
    public function up()
    {
        //admin_agents
        $this->createTable('{{%admin_dg_templates%}}', [
            'name' => $this->string(255)->notNull(),
            'file_path' => $this->string(255)->notNull(),
            'numerator_id' => $this->integer()->notNull(),
            'region_id' => $this->char(2)->notNull(),
            'code' => $this->string(32)->notNull(),
            'active' => $this->char(1)->notNull(),
            'with_stamps' => $this->char(1)->notNull(),
            'sort' => $this->integer()->notNull(),
            'template_id' => $this->integer()->null(),
        ]);
        $this->addPrimaryKey('code', 'admin_dg_templates', 'code');
    }

    public function down()
    {
        $this->dropTable('{{%admin_dg_templates%}}');
    }
}
