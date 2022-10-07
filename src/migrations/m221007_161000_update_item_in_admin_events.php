<?php

class m221007_161000_update_item_in_admin_events extends \yii\db\Migration
{
    public function up()
    {
        $this->update(
            'admin_events_directory',
            ['description' => 'Событие вызывается после добавления новой рабочей группы. Прокси к событию OnSocNetGroupAdd.'],
            ['name' => 'ONSONETGROUPADD']
        );
    }

    public function down()
    {
    }
}
