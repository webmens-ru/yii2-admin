<?php

class m220819_141200_update_admin_placement_directory extends \yii\db\Migration
{
    public function up()
    {
        $this->insert('admin_placement_directory', [
            'name_id' => 'PAGE_BACKGROUND_WORKER',
            'description' => 'Сценарий встройки WebRtc',
            'category_name' => 'Все страницы портала'
        ]);
    }

    public function down()
    {
    }
}
