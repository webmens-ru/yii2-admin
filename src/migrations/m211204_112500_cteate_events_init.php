<?php

use yii\db\Migration;

class m211204_112500_cteate_events_init extends Migration
{
    public function up()
    {
        //admin_events_directory
        $this->createTable('{{%admin_events_directory%}}', [
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
            'category_name' => $this->string(255)->notNull(),
        ]);
        $this->batchInsert(
            'admin_events_directory',
            [
                'name',
                'description',
                'category_name'
            ],
            [
                ['bizproc.event.send', 'Метод возвращает действию выходные параметры, заданные в описании действия', 'Бизнес-процессы'],
                ['OnAppInstall', 'при установке приложения', 'Приложение'],
                ['OnAppMethodConfirm', 'при получении решения администратора портала по запросу на использование методов, требующих подтверждения', 'Приложение'],
                ['OnAppPayment', 'при оплате приложения', 'Приложение'],
                ['OnAppUninstall', 'при удалении приложения', 'Приложение'],
                ['OnAppUpdate', 'при обновлении версии приложения', 'Приложение'],
                ['OnCalendarEntryAdd', 'при добавлении события.', 'Календарь'],
                ['OnCalendarEntryDelete', 'при удалении события.', 'Календарь'],
                ['OnCalendarEntryUpdate', 'при изменении события.', 'Календарь'],
                ['OnCalendarSectionAdd', 'при добавлении секции календаря . Также будет вызываться при добавлении ресурса.', 'Календарь'],
                ['OnCalendarSectionDelete', 'при удалении секции/ресурса.', 'Календарь'],
                ['OnCalendarSectionUpdate', 'при изменении секции/ресурса.', 'Календарь'],
                ['onCrmActivityAdd', 'при создании дела', 'Дела'],
                ['onCrmActivityDelete', 'при удалении дела', 'Дела'],
                ['onCrmActivityUpdate', 'при обновлении дела', 'Дела'],
                ['onCrmAddressRegister', 'при регистрации адреса', 'Реквизиты'],
                ['onCrmAddressUnregister', 'при удалении адреса', 'Реквизиты'],
                ['onCrmBankDetailAdd', 'при добавления банковского реквизита', 'Реквизиты'],
                ['onCrmBankDetailDelete', 'при удалении банковского реквизита.', 'Реквизиты'],
                ['onCrmBankDetailUpdate', 'при обновлении банковского реквизита', 'Реквизиты'],
                ['onCrmCompanyAdd', 'при создании компании', 'Компания'],
                ['onCrmCompanyDelete', 'при удалении компании', 'Компания'],
                ['onCrmCompanyUpdate', 'при обновлении компании', 'Компания'],
                ['onCrmCompanyUserFieldAdd', 'при добавлении пользовательского поля', 'Компания'],
                ['onCrmCompanyUserFieldDelete', 'при удалении пользовательского поля', 'Компания'],
                ['onCrmCompanyUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Компания'],
                ['onCrmCompanyUserFieldUpdate', 'при изменении пользовательского поля', 'Компания'],
                ['onCrmContactAdd', 'при создании контакта', 'Контакт'],
                ['onCrmContactDelete', 'при удалении контакта', 'Контакт'],
                ['onCrmContactUpdate', 'при обновлении контакта', 'Контакт'],
                ['onCrmContactUserFieldAdd', 'при добавлении пользовательского поля', 'Контакт'],
                ['onCrmContactUserFieldDelete', 'при удалении пользовательского поля', 'Контакт'],
                ['onCrmLeadUpdate', 'при обновлении лида', 'Лид'],
                ['onCrmLeadUserFieldAdd', 'при добавлении пользовательского поля', 'Лид'],
                ['onCrmLeadUserFieldDelete', 'при удалении пользовательского поля', 'Лид'],
                ['onCrmLeadUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Лид'],
                ['onCrmLeadUserFieldUpdate', 'при изменении пользовательского поля', 'Лид'],
                ['onCrmMeasureAdd', 'добавления новой единицы измерения на портале.', 'Единицы измерения'],
                ['onCrmMeasureDelete', 'удаления единицы измерения на портале.', 'Единицы измерения'],
                ['onCrmMeasureUpdate', 'изменения единицы измерения на портале.', 'Единицы измерения'],
                ['onCrmProductAdd', 'при создании товара', 'Товары'],
                ['onCrmProductDelete', 'при удалении товара', 'Товары'],
                ['onCrmProductPropertyAdd', 'при создании свойства товара', 'Товары'],
                ['onCrmProductPropertyDelete', 'при удалении свойства товара', 'Товары'],
                ['onCrmProductPropertyUpdate', 'при обновлении свойства товара', 'Товары'],
                ['onCrmProductSectionAdd', 'добавления раздела', 'Разделы товаров'],
                ['onCrmProductSectionDelete', 'удаления раздела', 'Разделы товаров'],
                ['onCrmProductSectionUpdate', 'изменения раздела', 'Разделы товаров'],
                ['onCrmProductUpdate', 'при обновлении товара', 'Товары'],
                ['onCrmQuoteAdd', 'при создании предложения', 'Коммерческое предложение'],
                ['onCrmQuoteDelete', 'при удалении предложения', 'Коммерческое предложение'],
                ['onCrmQuoteUpdate', 'при обновлении предложения', 'Коммерческое предложение'],
                ['onCrmQuoteUserFieldAdd', 'при добавлении пользовательского поля', 'Коммерческое предложение'],
                ['onCrmQuoteUserFieldDelete', 'при удалении пользовательского поля', 'Коммерческое предложение'],
                ['onCrmQuoteUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Коммерческое предложение'],
                ['onCrmQuoteUserFieldUpdate', 'при изменении пользовательского поля', 'Коммерческое предложение'],
                ['onCrmRequisiteAdd', 'при добавлении реквизита', 'Реквизиты'],
                ['onCrmRequisiteDelete', 'при удалении реквизита', 'Реквизиты'],
                ['onCrmRequisiteUpdate', 'при обновлении реквизита', 'Реквизиты'],
                ['onCrmRequisiteUserFieldAdd', 'при добавлении пользовательского поля', 'Реквизиты'],
                ['onCrmRequisiteUserFieldDelete', 'при удалении пользовательского поля', 'Реквизиты'],
                ['onCrmRequisiteUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Реквизиты'],
                ['onCrmRequisiteUserFieldUpdate', 'при изменении пользовательского поля', 'Реквизиты'],
                ['onCrmTimelineCommentAdd', 'при добавлении нового комментария в таймлайне', 'Таймлайн'],
                ['onCrmTimelineCommentDelete', 'при удалении нового комментария в таймлайне', 'Таймлайн'],
                ['onCrmTimelineCommentUpdate', 'при обновлении нового комментария в таймлайне', 'Таймлайн'],
                ['OnExternalCallBackStart', 'Вызывается, когда посетитель заполняет crm-форму обратного звонка. В настройках формы должно быть выбрано ваше приложение, как линия, через которую будет совершаться обратный звонок.', 'Телефония'],
                ['OnExternalCallStart', 'Вызывается, когда пользователь нажимает на телефонный номер в объектах CRM для совершения исходящего звонка.', 'Телефония'],
                ['OnImConnectorLineDelete', 'Событие удаления открытой линии.', 'Коннекторы для мессенджерей'],
                ['OnImConnectorMessageAdd', 'Событие нового сообщения из ОЛ.', 'Коннекторы для мессенджерей'],
                ['OnImConnectorMessageDelete', 'Событие удаления сообщения из ОЛ.', 'Коннекторы для мессенджерей'],
                ['OnImConnectorMessageUpdate', 'Событие изменения сообщения из ОЛ.', 'Коннекторы для мессенджерей'],
                ['ONLIVEFEEDPOSTADD', 'Прокси к событию PHP OnAfterSocNetLogAdd.', 'Живая лента'],
                ['ONLIVEFEEDPOSTDELETE', 'Прокси к событию PHP OnSocNetLogDelete', 'Живая лента'],
                ['ONLIVEFEEDPOSTUPDATE', 'Прокси к событию PHP OnAfterSocNetLogUpdate.', 'Живая лента'],
                ['OnSaleBeforeOrderDelete', 'Вызывается перед удалением заказа.', 'Интернет-магазин'],
                ['OnSaleOrderSaved', 'Происходит в конце сохранения заказа, когда заказ и все связанные сущности уже сохранены.', 'Интернет-магазин'],
                ['ONSONETGROUPADD', '', 'Рабочие группы соцсети'],
                ['ONSONETGROUPDELETE', 'Вызывается в момент удаления рабочей группы. Прокси к событию OnSocNetGroupDelete', 'Рабочие группы соцсети'],
                ['ONSONETGROUPSUBJECTADD', 'Событие вызывается после создания темы рабочих групп. Прокси к событию OnSocNetGroupSubjectAdd.', 'Рабочие группы соцсети'],
                ['ONSONETGROUPSUBJECTDELETE', 'Вызывается перед удалением темы рабочих групп.. Прокси к событию OnSocNetGroupSubjectDelete', 'Рабочие группы соцсети'],
                ['ONSONETGROUPSUBJECTUPDATE', 'Событие вызывается после изменения темы рабочих групп. Прокси к событию OnSocNetGroupSubjectUpdate', 'Рабочие группы соцсети'],
                ['ONSONETGROUPUPDATE', 'Событие вызывается после изменения рабочей группы. Прокси к событию onSocnetGroupUpdate', 'Рабочие группы соцсети'],
                ['OnTaskAdd', 'при добавлении задачи.', 'Задачи'],
                ['OnTaskCommentAdd', 'при добавлении комментария к задаче.', 'Задачи'],
                ['OnTaskCommentUpdate', 'при проведении операций над комментарием к задаче.', 'Задачи'],
                ['OnTaskDelete', 'при удалении задачи.', 'Задачи'],
                ['OnTaskUpdate', 'при обновлении задачи.', 'Задачи'],
                ['OnUserAdd', 'при добавлении пользователя в Битрикс24', 'Портал'],
            ]
        );
        $this->addPrimaryKey('name', 'admin_events_directory', 'name');

        //admin_robots_types
        $this->createTable('{{%admin_events%}}', [
            'id' => $this->primaryKey(),
            'event_name' => $this->string(255)->notNull(),
            'handler' => $this->string(255)->notNull(),
            'auth_type' => $this->integer()->null(),
            'event_type' => $this->string(10)->notNull(),
        ]);
        $this->addForeignKey('admin_events_fk0', 'admin_events', ['event_name'], 'admin_events_directory', ['name'], 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%admin_events%}}');
        $this->dropTable('{{%admin_events_directory%}}');
    }
}
