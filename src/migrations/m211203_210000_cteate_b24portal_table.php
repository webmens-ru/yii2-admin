<?php

use yii\db\Migration;

class m211203_210000_cteate_b24portal_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%admin_b24portal%}}', [
            'PORTAL' => $this->string(255)->notNull(),
            'ACCESS_TOKEN' => $this->string(70)->notNull(),
            'REFRESH_TOKEN' => $this->string(70)->notNull(),
            'MEMBER_ID' => $this->string(32)->notNull(),
            'DATE' => $this->date()->notNull(),
            'PRIMARY KEY(PORTAL)',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%admin_b24portal%}}');
    }
}
