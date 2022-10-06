<?php

class m221006_115000_add_admin_placement_directory extends \yii\db\Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_placement_directory',
            [
                'name_id',
                'description',
                'category_name',
            ],
            [
                ['CRM_DYNAMIC__LIST_MENU', 'Контекстное меню', 'Смарт-процесс'],
                ['CRM_DYNAMIC__DETAIL_TAB', 'Пункт в верхнем меню', 'Смарт-процесс'],
                ['CRM_DYNAMIC__DETAIL_ACTIVITY', 'Пункт в меню таймлайна', 'Смарт-процесс']
            ]
        );
    }

    public function down()
    {
    }
}
