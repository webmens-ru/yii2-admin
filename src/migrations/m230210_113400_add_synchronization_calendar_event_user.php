<?php

class m230210_113400_add_synchronization_calendar_event_user extends \yii\db\Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_synchronization',
            [
                'title',
                'active',
                'modelClassName'
            ],
            [
                ['События календарей пользователей', 0, '\wm\admin\models\synchronization\CalendarEventUser'],
            ]
        );
    }

    public function down()
    {
    }
}
