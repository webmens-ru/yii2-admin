<?php

class m231132_135300_add_event_on_crm_timeline_item_action extends \yii\db\Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_events_directory',
            [
                'name',
                'description',
                'category_name'
            ],
            [
                ['onCrmTimelineItemAction', 'при выполнении действия в конфигурируемом деле', 'Таймлайн'],
            ]
        );
    }

    public function down()
    {
    }
}
