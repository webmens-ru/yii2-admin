<?php

class m221014_105400_add_admin_events_directory extends \yii\db\Migration
{
    public function up()
    {
        $this->batchInsert(
            'admin_events_directory',
            [
                'name',
                'description',
                'category_name',
            ],
            [
                ['onCrmDynamicItemAdd_', 'Добавление элемента смарт-процесса', 'Смарт-процесс'],
                ['onCrmDynamicItemUpdate_', 'Изменение элемента смарт-процесса', 'Смарт-процесс'],
                ['onCrmDynamicItemDelete_', 'Удаление элементао смарт-процесса', 'Смарт-процесс']
            ]
        );
    }

    public function down()
    {
    }
}
