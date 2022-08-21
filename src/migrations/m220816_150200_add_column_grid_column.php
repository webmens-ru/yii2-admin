<?php

class m220816_150200_add_column_grid_column extends \yii\db\Migration
{
    public function up()
    {
        $this->update('{{%admin_grid_column}}', [
            'frozen' => $this->boolean()->defaultValue(0),
            'reordering' => $this->boolean()->defaultValue(1),
            'resizeble' => $this->boolean()->defaultValue(1),
            'sortable' => $this->boolean()->defaultValue(1),
        ]);
    }

    public function down()
    {
    }
}
