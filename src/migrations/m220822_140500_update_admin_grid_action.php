<?php

class m220822_140500_update_admin_grid_action extends \yii\db\Migration
{
    public function up()
    {
        $this->update('{{%admin_grid_action}}', [
            'params' => $this->text(),
        ]);
    }

    public function down()
    {
    }
}
