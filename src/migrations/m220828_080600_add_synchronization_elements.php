<?php

use yii\db\Migration;

class m220828_080600_add_synchronization_elements extends Migration
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
                ['Лиды', 0, '\wm\admin\models\synchronization\Lead'],
                ['Сделки', 0, '\wm\admin\models\synchronization\Deal'],
                ['Контакты', 0, '\wm\admin\models\synchronization\Contact'],
                ['Компании', 0, '\wm\admin\models\synchronization\Company'],
                ['Пользователи', 0, '\wm\admin\models\synchronization\Employee'],
                ['Дела', 0, '\wm\admin\models\synchronization\Telephony'],
                ['Телефония', 0, '\wm\admin\models\synchronization\Activity'],
                ['Задачи', 0, '\wm\admin\models\synchronization\Task'],
            ]
        );
    }

    public function down()
    {
    }
}
