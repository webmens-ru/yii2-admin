<?php

class m220816_150600_add_column_grid_column_personal extends \yii\db\Migration
{
    public function up()
    {
        $this->update('{{%admin_grid_column_personal}}', [
            'frozen' => $this->boolean()
        ]);
    }

    public function down()
    {
    }
}
