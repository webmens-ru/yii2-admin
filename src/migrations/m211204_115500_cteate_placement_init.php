<?php

use yii\db\Migration;

class m211204_115500_cteate_placement_init extends Migration
{
    public function up()
    {
        //admin_events_directory
        $this->createTable('{{%admin_placement_directory%}}', [
            'name_id' => $this->string(50)->notNull(),
            'description' => $this->string(255)->notNull(),
            'category_name' => $this->string(255)->notNull(),
        ]);
        $this->batchInsert(
            'admin_placement_directory',
            [
                'name_id',
                'description',
                'category_name'
            ],
            [
                ['CALENDAR_GRIDVIEW', 'Список видов отображения календаря', 'Календарь'],
                ['CALL_CARD', 'Карточка звонка', 'Карточка звонка'],
                ['CONTACT_CENTER', '\"Квадратик\" в списке Контакт центра.', 'Контакты'],
                ['CRM_ACTIVITY_LIST_MENU', 'Контекстное меню дел', 'Дела'],
                ['CRM_ANALYTICS_MENU', 'Меню CRM-аналитики', 'CRM-аналитика'],
                ['CRM_ANALYTICS_TOOLBAR', 'Кнопка в тулбаре CRM-аналитики', 'CRM-аналитика'],
                ['CRM_COMPANY_DETAIL_ACTIVITY', 'Пункт в меню таймлайна компании', 'Компании'],
                ['CRM_COMPANY_DETAIL_TAB', 'Пункт в верхнем меню в карточке компании', 'Компании'],
                ['CRM_COMPANY_DETAIL_TOOLBAR', 'Пункт в списке приложений карточки компании.', 'Компании'],
                ['CRM_COMPANY_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Компании'],
                ['CRM_COMPANY_LIST_MENU', 'Контекстное меню компаний', 'Компании'],
                ['CRM_COMPANY_LIST_TOOLBAR', 'Кнопка около Роботов', 'Компании'],
                ['CRM_CONTACT_DETAIL_ACTIVITY', 'Пункт в меню таймлайна контакта', 'Контакты'],
                ['CRM_CONTACT_DETAIL_TAB', 'Пункт в верхнем меню в карточке контакта', 'Контакты'],
                ['CRM_CONTACT_DETAIL_TOOLBAR', 'Пункт в списке приложений карточки контакта.', 'Контакты'],
                ['CRM_CONTACT_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Контакты'],
                ['CRM_CONTACT_LIST_MENU', 'Контекстное меню контактов', 'Контакты'],
                ['CRM_CONTACT_LIST_TOOLBAR', 'Кнопка около Роботов', 'Контакты'],
                ['CRM_DEAL_ACTIVITY_TIMELINE_MENU', 'Кнопка в контекстном меню объекта', 'Сделки'],
                ['CRM_DEAL_DETAIL_ACTIVITY', 'Пункт в меню таймлайна сделки', 'Сделки'],
                ['CRM_DEAL_DETAIL_TAB', 'Пункт в верхнем меню в карточке сделки', 'Сделки'],
                ['CRM_DEAL_DETAIL_TOOLBAR', 'Пункт в списке приложений карточки сделки.', 'Сделки'],
                ['CRM_DEAL_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Сделки'],
                ['CRM_DEAL_LIST_MENU', 'Контекстное меню сделок', 'Сделки'],
                ['CRM_DEAL_LIST_TOOLBAR', 'Кнопка около Роботов', 'Сделки'],
                ['CRM_DEAL_ROBOT_DESIGNER_TOOLBAR', 'Кнопка в слайдере с роботами', 'Сделки'],
                ['CRM_FUNNELS_TOOLBAR', 'Кнопка в тулбаре Тунелей продаж', 'СRM'],
                ['CRM_INVOICE_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Счета'],
                ['CRM_INVOICE_LIST_MENU', 'Контекстное меню счетов', 'Счета'],
                ['CRM_INVOICE_LIST_TOOLBAR', 'Кнопка около Роботов', 'Счета'],
                ['CRM_LEAD_ACTIVITY_TIMELINE_MENU', 'Кнопка в контекстном меню объекта', 'Лиды'],
                ['CRM_LEAD_DETAIL_ACTIVITY', 'Пункт в меню таймлайна лида', 'Лиды'],
                ['CRM_LEAD_DETAIL_TAB', 'Пункт в верхнем меню в карточке лида', 'Лиды'],
                ['CRM_LEAD_DETAIL_TOOLBAR', 'Пункт в списке приложений карточки лида', 'Лиды'],
                ['CRM_LEAD_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Лиды'],
                ['CRM_LEAD_LIST_MENU', 'Контекстное меню лидов', 'Лиды'],
                ['CRM_LEAD_LIST_TOOLBAR', 'Кнопка около Роботов', 'Лиды'],
                ['CRM_LEAD_ROBOT_DESIGNER_TOOLBAR', 'Кнопка в слайдере с роботами', 'Лиды'],
                ['CRM_QUOTE_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Предложения'],
                ['CRM_QUOTE_LIST_MENU', 'Контекстное меню предложений', 'Предложения'],
                ['CRM_QUOTE_LIST_TOOLBAR', 'Кнопка около Роботов', 'Предложения'],
                ['LANDING_BLOCK', 'Пункт редактирования любого блока.', 'Landing'],
                ['LANDING_SETTINGS', 'Меню настроек [Страницы / Сайта]', 'Landing'],
                ['REST_APP_URI', 'Ссылки', 'Произвольное место'],
                ['SONET_GROUP_DETAIL_TAB', 'Закладка рабочей группы.', 'Рабочие группы'],
                ['SONET_GROUP_ROBOT_DESIGNER_TOOLBAR', 'Кнопка у роботов в группе.', 'Рабочие группы'],
                ['SONET_GROUP_TOOLBAR', 'Кнопка на вкладке Основное в группе.', 'Рабочие группы'],
                ['TASK_GROUP_LIST_TOOLBAR', 'Кнопка около Роботов.', 'Задачи'],
                ['TASK_LIST_CONTEXT_MENU', 'Контекстное меню списка задач.', 'Задачи'],
                ['TASK_ROBOT_DESIGNER_TOOLBAR', 'Кнопка в слайдере с роботами.', 'Задачи'],
                ['TASK_USER_LIST_TOOLBAR', 'Кнопка около Роботов.', 'Задачи'],
                ['TASK_VIEW_SIDEBAR', 'Боковая панель формы просмотра задачи', 'Задачи'],
                ['TASK_VIEW_TAB', 'Вкладка в форме просмотра задачи', 'Задачи'],
                ['TASK_VIEW_TOP_PANEL', 'Пункт в вехнем меню формы просмотра задачи', 'Задачи'],
                ['USER_PROFILE_MENU', 'Кнопка в главном меню портала.', 'Профиль пользователя'],
                ['USER_PROFILE_TOOLBAR', 'Кнопка в профиле .', 'Профиль пользователя'],
            ]
        );
        $this->addPrimaryKey('name_id', 'admin_placement_directory', 'name_id');

        //admin_robots_types
        $this->createTable('{{%admin_placement%}}', [
            'id' => $this->primaryKey(),
            'placement_name' => $this->string(50)->notNull(),
            'handler' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->string(255)->null(),
            'group_name' => $this->string(255)->null(),
        ]);
        $this->addForeignKey('admin_placement_ibfk_0', 'admin_placement', ['placement_name'], 'admin_placement_directory', ['name_id']);
    }

    public function down()
    {
        $this->dropTable('{{%admin_placement%}}');
        $this->dropTable('{{%admin_placement_directory%}}');
    }
}
