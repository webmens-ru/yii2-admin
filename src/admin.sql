SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Структура таблицы `admin_b24portal`
--

CREATE TABLE IF NOT EXISTS `admin_b24portal` (
  `PORTAL` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ACCESS_TOKEN` char(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `REFRESH_TOKEN` char(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MEMBER_ID` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_settings`
--

CREATE TABLE `admin_settings` (
  `name_id` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_b24_connect_settings`
--

CREATE TABLE IF NOT EXISTS `admin_b24_connect_settings` (
  `name_id` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin_b24_connect_settings`
--

INSERT INTO `admin_b24_connect_settings` (`name_id`, `value`, `name`) VALUES
('applicationId', 'local.000000000000000', 'applicationId'),
('applicationSecret', '00000000000000000', 'applicationSecret'),
('b24PortalName', '00.bitrix24.ru', 'b24PortalName'),
('b24PortalTable', 'admin_b24portal', 'b24PortalTable');

-- --------------------------------------------------------

--
-- Структура таблицы `admin_robots`
--

CREATE TABLE `admin_robots` (
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `handler` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `use_subscription` tinyint(1) DEFAULT NULL,
  `use_placement` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Структура таблицы `admin_robots_options`
--

CREATE TABLE `admin_robots_options` (
  `property_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `robot_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_robots_properties`
--

CREATE TABLE `admin_robots_properties` (
  `robot_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_in` int(1) DEFAULT NULL,
  `system_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `required` int(1) NOT NULL,
  `multiple` int(1) NOT NULL,
  `default` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_robots_types`
--

CREATE TABLE `admin_robots_types` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_events`
--

CREATE TABLE `admin_events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `handler` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_type` int(11) DEFAULT NULL,
  `event_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_events_directory`
--

CREATE TABLE `admin_events_directory` (
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Структура таблицы `admin_placement`
--

CREATE TABLE `admin_placement` (
  `id` int(11) NOT NULL,
  `placement_name` varchar(50) NOT NULL,
  `handler` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `group_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_placement_directory`
--

CREATE TABLE `admin_placement_directory` (
  `name_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Структура таблицы `admin_chatbot_app_js_method_directory`
--
-- Создание: Июл 04 2021 г., 12:04
--

CREATE TABLE `admin_chatbot_app_js_method_directory` (
  `code` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_chatbot_color_directory`
--
-- Создание: Июн 23 2021 г., 15:46
--

CREATE TABLE `admin_chatbot_color_directory` (
  `name` varchar(20) NOT NULL COMMENT 'Имя',
  `title` varchar(255) DEFAULT NULL COMMENT 'Описание'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_chatbot_command`
--
-- Создание: Июн 24 2021 г., 17:03
--

CREATE TABLE `admin_chatbot_command` (
  `bot_code` varchar(64) NOT NULL,
  `command` varchar(255) NOT NULL,
  `common` char(1) NOT NULL,
  `hidden` char(1) NOT NULL,
  `extranet_support` char(1) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `params_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `params_en` varchar(255) NOT NULL,
  `event_command_add` varchar(255) NOT NULL,
  `command_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_chatbot_type_directory`
--
-- Создание: Июн 23 2021 г., 15:45
--

CREATE TABLE `admin_chatbot_type_directory` (
  `name` char(1) NOT NULL COMMENT 'Имя',
  `title` varchar(255) NOT NULL COMMENT 'Описание'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `admin_dg_templates`
--
-- Создание: Июл 28 2021 г., 06:40
--

CREATE TABLE `admin_dg_templates` (
  `name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `numerator_id` int(11) NOT NULL,
  `region_id` char(2) NOT NULL,
  `code` varchar(32) NOT NULL,
  `active` char(1) NOT NULL,
  `with_stamps` char(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `template_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `admin_chatbot_app_contex_directory`
--
-- Создание: Июл 04 2021 г., 12:04
--

CREATE TABLE `admin_chatbot_app_contex_directory` (
  `code` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `admin_chatbot`
--
-- Создание: Июн 24 2021 г., 04:58
--

CREATE TABLE `admin_chatbot` (
  `code` varchar(255) NOT NULL COMMENT 'Код',
  `type_id` char(1) NOT NULL COMMENT 'Тип',
  `openline` char(1) DEFAULT NULL COMMENT 'Является открытой линией',
  `p_name` varchar(255) NOT NULL COMMENT 'Имя',
  `p_last_name` varchar(255) NOT NULL COMMENT 'Фамилия',
  `p_color_name` varchar(20) NOT NULL COMMENT 'Цвет',
  `p_email` varchar(255) NOT NULL COMMENT 'email',
  `p_personal_birthday` date DEFAULT NULL COMMENT 'День рождения',
  `p_work_position` varchar(255) DEFAULT NULL COMMENT 'Должность',
  `p_personal_www` varchar(255) DEFAULT NULL COMMENT 'Персональный сайт',
  `p_personal_gender` tinyint(1) DEFAULT NULL COMMENT 'Пол',
  `p_personal_photo_url` varchar(255) DEFAULT NULL COMMENT 'Фото',
  `event_handler` varchar(255) DEFAULT NULL,
  `event_massege_add` varchar(255) DEFAULT NULL,
  `event_massege_update` varchar(255) DEFAULT NULL,
  `event_massege_delete` varchar(255) DEFAULT NULL,
  `event_welcome_massege` varchar(255) DEFAULT NULL,
  `event_bot_delete` varchar(255) DEFAULT NULL,
  `bot_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Структура таблицы `admin_chatbot_app`
--
-- Создание: Июл 04 2021 г., 18:06
--

CREATE TABLE `admin_chatbot_app` (
  `bot_code` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL,
  `js_method_code` varchar(32) DEFAULT NULL,
  `js_param` varchar(32) DEFAULT NULL,
  `icon_file` text,
  `contex_code` varchar(32) NOT NULL,
  `extranet_support` char(1) NOT NULL,
  `iframe_popup` char(1) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `iframe` varchar(255) DEFAULT NULL,
  `iframe_height` int(11) DEFAULT NULL,
  `iframe_width` int(11) DEFAULT NULL,
  `hash` char(32) DEFAULT NULL,
  `hidden` varchar(1) DEFAULT NULL,
  `livechat_support` varchar(1) NOT NULL,
  `type` varchar(32) NOT NULL,
  `app_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `admin_agents`
--

CREATE TABLE `admin_agents` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `params` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_run` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `period` int(11) NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Дамп данных таблицы `admin_robots_types`
--

INSERT INTO `admin_robots_types` (`id`, `name`) VALUES
(1, 'bool'),
(2, 'date'),
(3, 'datetime'),
(4, 'double'),
(5, 'int'),
(6, 'select_dynamic'),
(7, 'select_static'),
(8, 'string'),
(9, 'text'),
(10, 'user');


--
-- Дамп данных таблицы `admin_placement_directory`
--

INSERT INTO `admin_placement_directory` (`name_id`, `description`, `category_name`) VALUES
('CALENDAR_GRIDVIEW', 'Список видов отображения календаря', 'Календарь'),
('CALL_CARD', 'Карточка звонка', 'Карточка звонка'),
('CONTACT_CENTER', '\"Квадратик\" в списке Контакт центра.', 'Контакты'),
('CRM_ACTIVITY_LIST_MENU', 'Контекстное меню дел', 'Дела'),
('CRM_ANALYTICS_MENU', 'Меню CRM-аналитики', 'CRM-аналитика'),
('CRM_ANALYTICS_TOOLBAR', 'Кнопка в тулбаре CRM-аналитики', 'CRM-аналитика'),
('CRM_COMPANY_DETAIL_ACTIVITY', 'Пункт в меню таймлайна компании', 'Компании'),
('CRM_COMPANY_DETAIL_TAB', 'Пункт в верхнем меню в карточке компании', 'Компании'),
('CRM_COMPANY_DETAIL_TOOLBAR', 'Пункт в списке приложений карточки компании.', 'Компании'),
('CRM_COMPANY_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Компании'),
('CRM_COMPANY_LIST_MENU', 'Контекстное меню компаний', 'Компании'),
('CRM_COMPANY_LIST_TOOLBAR', 'Кнопка около Роботов', 'Компании'),
('CRM_CONTACT_DETAIL_ACTIVITY', 'Пункт в меню таймлайна контакта', 'Контакты'),
('CRM_CONTACT_DETAIL_TAB', 'Пункт в верхнем меню в карточке контакта', 'Контакты'),
('CRM_CONTACT_DETAIL_TOOLBAR', 'Пункт в списке приложений карточки контакта.', 'Контакты'),
('CRM_CONTACT_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Контакты'),
('CRM_CONTACT_LIST_MENU', 'Контекстное меню контактов', 'Контакты'),
('CRM_CONTACT_LIST_TOOLBAR', 'Кнопка около Роботов', 'Контакты'),
('CRM_DEAL_ACTIVITY_TIMELINE_MENU', 'Кнопка в контекстном меню объекта', 'Сделки'),
('CRM_DEAL_DETAIL_ACTIVITY', 'Пункт в меню таймлайна сделки', 'Сделки'),
('CRM_DEAL_DETAIL_TAB', 'Пункт в верхнем меню в карточке сделки', 'Сделки'),
('CRM_DEAL_DETAIL_TOOLBAR', 'Пункт в списке приложений карточки сделки.', 'Сделки'),
('CRM_DEAL_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Сделки'),
('CRM_DEAL_LIST_MENU', 'Контекстное меню сделок', 'Сделки'),
('CRM_DEAL_LIST_TOOLBAR', 'Кнопка около Роботов', 'Сделки'),
('CRM_DEAL_ROBOT_DESIGNER_TOOLBAR', 'Кнопка в слайдере с роботами', 'Сделки'),
('CRM_FUNNELS_TOOLBAR', 'Кнопка в тулбаре Тунелей продаж', 'СRM'),
('CRM_INVOICE_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Счета'),
('CRM_INVOICE_LIST_MENU', 'Контекстное меню счетов', 'Счета'),
('CRM_INVOICE_LIST_TOOLBAR', 'Кнопка около Роботов', 'Счета'),
('CRM_LEAD_ACTIVITY_TIMELINE_MENU', 'Кнопка в контекстном меню объекта', 'Лиды'),
('CRM_LEAD_DETAIL_ACTIVITY', 'Пункт в меню таймлайна лида', 'Лиды'),
('CRM_LEAD_DETAIL_TAB', 'Пункт в верхнем меню в карточке лида', 'Лиды'),
('CRM_LEAD_DETAIL_TOOLBAR', 'Пункт в списке приложений карточки лида', 'Лиды'),
('CRM_LEAD_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Лиды'),
('CRM_LEAD_LIST_MENU', 'Контекстное меню лидов', 'Лиды'),
('CRM_LEAD_LIST_TOOLBAR', 'Кнопка около Роботов', 'Лиды'),
('CRM_LEAD_ROBOT_DESIGNER_TOOLBAR', 'Кнопка в слайдере с роботами', 'Лиды'),
('CRM_QUOTE_DOCUMENTGENERATOR_BUTTON', 'Кнопка в документах', 'Предложения'),
('CRM_QUOTE_LIST_MENU', 'Контекстное меню предложений', 'Предложения'),
('CRM_QUOTE_LIST_TOOLBAR', 'Кнопка около Роботов', 'Предложения'),
('LANDING_BLOCK', 'Пункт редактирования любого блока.', 'Landing'),
('LANDING_SETTINGS', 'Меню настроек (Страницы / Сайта)', 'Landing'),
('REST_APP_URI', 'Ссылки', 'Произвольное место'),
('SONET_GROUP_DETAIL_TAB', 'Закладка рабочей группы.', 'Рабочие группы'),
('SONET_GROUP_ROBOT_DESIGNER_TOOLBAR', 'Кнопка у роботов в группе.', 'Рабочие группы'),
('SONET_GROUP_TOOLBAR', 'Кнопка на вкладке Основное в группе.', 'Рабочие группы'),
('TASK_GROUP_LIST_TOOLBAR', 'Кнопка около Роботов.', 'Задачи'),
('TASK_LIST_CONTEXT_MENU', 'Контекстное меню списка задач.', 'Задачи'),
('TASK_ROBOT_DESIGNER_TOOLBAR', 'Кнопка в слайдере с роботами.', 'Задачи'),
('TASK_USER_LIST_TOOLBAR', 'Кнопка около Роботов.', 'Задачи'),
('TASK_VIEW_SIDEBAR', 'Боковая панель формы просмотра задачи', 'Задачи'),
('TASK_VIEW_TAB', 'Вкладка в форме просмотра задачи', 'Задачи'),
('TASK_VIEW_TOP_PANEL', 'Пункт в вехнем меню формы просмотра задачи', 'Задачи'),
('USER_PROFILE_MENU', 'Кнопка в главном меню портала.', 'Профиль пользователя'),
('USER_PROFILE_TOOLBAR', 'Кнопка в профиле .', 'Профиль пользователя');

INSERT INTO `admin_events_directory` (`name`, `description`, `category_name`) VALUES
('bizproc.event.send', 'Метод возвращает действию выходные параметры, заданные в описании действия', 'Бизнес-процессы'),
('OnAppInstall', 'при установке приложения', 'Приложение'),
('OnAppMethodConfirm', 'при получении решения администратора портала по запросу на использование методов, требующих подтверждения', 'Приложение'),
('OnAppPayment', 'при оплате приложения', 'Приложение'),
('OnAppUninstall', 'при удалении приложения', 'Приложение'),
('OnAppUpdate', 'при обновлении версии приложения', 'Приложение'),
('OnCalendarEntryAdd', 'при добавлении события.', 'Календарь'),
('OnCalendarEntryDelete', 'при удалении события.', 'Календарь'),
('OnCalendarEntryUpdate', 'при изменении события.', 'Календарь'),
('OnCalendarSectionAdd', 'при добавлении секции календаря . Также будет вызываться при добавлении ресурса.', 'Календарь'),
('OnCalendarSectionDelete', 'при удалении секции/ресурса.', 'Календарь'),
('OnCalendarSectionUpdate', 'при изменении секции/ресурса.', 'Календарь'),
('onCrmActivityAdd', 'при создании дела', 'Дела'),
('onCrmActivityDelete', 'при удалении дела', 'Дела'),
('onCrmActivityUpdate', 'при обновлении дела', 'Дела'),
('onCrmAddressRegister', 'при регистрации адреса', 'Реквизиты'),
('onCrmAddressUnregister', 'при удалении адреса', 'Реквизиты'),
('onCrmBankDetailAdd', 'при добавления банковского реквизита', 'Реквизиты'),
('onCrmBankDetailDelete', 'при удалении банковского реквизита.', 'Реквизиты'),
('onCrmBankDetailUpdate', 'при обновлении банковского реквизита', 'Реквизиты'),
('onCrmCompanyAdd', 'при создании компании', 'Компания'),
('onCrmCompanyDelete', 'при удалении компании', 'Компания'),
('onCrmCompanyUpdate', 'при обновлении компании', 'Компания'),
('onCrmCompanyUserFieldAdd', 'при добавлении пользовательского поля', 'Компания'),
('onCrmCompanyUserFieldDelete', 'при удалении пользовательского поля', 'Компания'),
('onCrmCompanyUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Компания'),
('onCrmCompanyUserFieldUpdate', 'при изменении пользовательского поля', 'Компания'),
('onCrmContactAdd', 'при создании контакта', 'Контакт'),
('onCrmContactDelete', 'при удалении контакта', 'Контакт'),
('onCrmContactUpdate', 'при обновлении контакта', 'Контакт'),
('onCrmContactUserFieldAdd', 'при добавлении пользовательского поля', 'Контакт'),
('onCrmContactUserFieldDelete', 'при удалении пользовательского поля', 'Контакт'),
('onCrmContactUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Контакт'),
('onCrmContactUserFieldUpdate', 'при изменении пользовательского поля', 'Контакт'),
('onCrmCurrencyAdd', 'после создании валюты', 'Валюты'),
('onCrmCurrencyDelete', 'после удалении валюты', 'Валюты'),
('onCrmCurrencyUpdate', 'после обновлении валюты', 'Валюты'),
('onCrmDealAdd', 'при создании сделки', 'Сделка'),
('onCrmDealDelete', 'при удалении сделки', 'Сделка'),
('onCrmDealRecurringAdd', 'при создании новой регулярной сделки', 'Сделка'),
('onCrmDealRecurringDelete', 'при удалении регулярной сделки', 'Сделка'),
('onCrmDealRecurringExpose', 'при создании новой сделки из регулярной сделки', 'Сделка'),
('onCrmDealRecurringUpdate', 'при обновлении регулярной сделки', 'Сделка'),
('onCrmDealUpdate', 'при обновлении сделки', 'Сделка'),
('onCrmDealUserFieldAdd', 'при добавлении пользовательского поля', 'Сделка'),
('onCrmDealUserFieldDelete', 'при удалении пользовательского поля', 'Сделка'),
('onCrmDealUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Сделка'),
('onCrmDealUserFieldUpdate', 'при изменении пользовательского поля', 'Сделка'),
('onCrmInvoiceAdd', 'при создании счёта', 'Счета'),
('onCrmInvoiceDelete', 'при удалении счёта', 'Счета'),
('onCrmInvoiceRecurringAdd', 'при создании нового регулярного счета', 'Счета'),
('onCrmInvoiceRecurringDelete', 'при удалении регулярного счета', 'Счета'),
('onCrmInvoiceRecurringExpose', 'при выставлении нового счета из регулярного счета', 'Счета'),
('onCrmInvoiceRecurringUpdate', 'при обновлении регулярного счета', 'Счета'),
('onCrmInvoiceSetStatus', 'при изменении статуса счёта', 'Счета'),
('onCrmInvoiceUpdate', 'при обновлении счёта', 'Счета'),
('onCrmInvoiceUserFieldAdd', 'при добавлении пользовательского поля', 'Счета'),
('onCrmInvoiceUserFieldDelete', 'при удалении пользовательского поля', 'Счета'),
('onCrmInvoiceUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Счета'),
('onCrmInvoiceUserFieldUpdate', 'при изменении пользовательского поля', 'Счета'),
('onCrmLeadAdd', 'при создании лида', 'Лид'),
('onCrmLeadDelete', 'при удалении лида', 'Лид'),
('onCrmLeadUpdate', 'при обновлении лида', 'Лид'),
('onCrmLeadUserFieldAdd', 'при добавлении пользовательского поля', 'Лид'),
('onCrmLeadUserFieldDelete', 'при удалении пользовательского поля', 'Лид'),
('onCrmLeadUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Лид'),
('onCrmLeadUserFieldUpdate', 'при изменении пользовательского поля', 'Лид'),
('onCrmMeasureAdd', 'добавления новой единицы измерения на портале.', 'Единицы измерения'),
('onCrmMeasureDelete', 'удаления единицы измерения на портале.', 'Единицы измерения'),
('onCrmMeasureUpdate', 'изменения единицы измерения на портале.', 'Единицы измерения'),
('onCrmProductAdd', 'при создании товара', 'Товары'),
('onCrmProductDelete', 'при удалении товара', 'Товары'),
('onCrmProductPropertyAdd', 'при создании свойства товара', 'Товары'),
('onCrmProductPropertyDelete', 'при удалении свойства товара', 'Товары'),
('onCrmProductPropertyUpdate', 'при обновлении свойства товара', 'Товары'),
('onCrmProductSectionAdd', 'добавления раздела', 'Разделы товаров'),
('onCrmProductSectionDelete', 'удаления раздела', 'Разделы товаров'),
('onCrmProductSectionUpdate', 'изменения раздела', 'Разделы товаров'),
('onCrmProductUpdate', 'при обновлении товара', 'Товары'),
('onCrmQuoteAdd', 'при создании предложения', 'Коммерческое предложение'),
('onCrmQuoteDelete', 'при удалении предложения', 'Коммерческое предложение'),
('onCrmQuoteUpdate', 'при обновлении предложения', 'Коммерческое предложение'),
('onCrmQuoteUserFieldAdd', 'при добавлении пользовательского поля', 'Коммерческое предложение'),
('onCrmQuoteUserFieldDelete', 'при удалении пользовательского поля', 'Коммерческое предложение'),
('onCrmQuoteUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Коммерческое предложение'),
('onCrmQuoteUserFieldUpdate', 'при изменении пользовательского поля', 'Коммерческое предложение'),
('onCrmRequisiteAdd', 'при добавлении реквизита', 'Реквизиты'),
('onCrmRequisiteDelete', 'при удалении реквизита', 'Реквизиты'),
('onCrmRequisiteUpdate', 'при обновлении реквизита', 'Реквизиты'),
('onCrmRequisiteUserFieldAdd', 'при добавлении пользовательского поля', 'Реквизиты'),
('onCrmRequisiteUserFieldDelete', 'при удалении пользовательского поля', 'Реквизиты'),
('onCrmRequisiteUserFieldSetEnumValues', 'при изменении набора значений для пользовательского поля списочного типа', 'Реквизиты'),
('onCrmRequisiteUserFieldUpdate', 'при изменении пользовательского поля', 'Реквизиты'),
('onCrmTimelineCommentAdd', 'при добавлении нового комментария в таймлайне', 'Таймлайн'),
('onCrmTimelineCommentDelete', 'при удалении нового комментария в таймлайне', 'Таймлайн'),
('onCrmTimelineCommentUpdate', 'при обновлении нового комментария в таймлайне', 'Таймлайн'),
('OnExternalCallBackStart', 'Вызывается, когда посетитель заполняет crm-форму обратного звонка. В настройках формы должно быть выбрано ваше приложение, как линия, через которую будет совершаться обратный звонок.', 'Телефония'),
('OnExternalCallStart', 'Вызывается, когда пользователь нажимает на телефонный номер в объектах CRM для совершения исходящего звонка.', 'Телефония'),
('OnImConnectorLineDelete', 'Событие удаления открытой линии.', 'Коннекторы для мессенджерей'),
('OnImConnectorMessageAdd', 'Событие нового сообщения из ОЛ.', 'Коннекторы для мессенджерей'),
('OnImConnectorMessageDelete', 'Событие удаления сообщения из ОЛ.', 'Коннекторы для мессенджерей'),
('OnImConnectorMessageUpdate', 'Событие изменения сообщения из ОЛ.', 'Коннекторы для мессенджерей'),
('ONLIVEFEEDPOSTADD', 'Прокси к событию PHP OnAfterSocNetLogAdd.', 'Живая лента'),
('ONLIVEFEEDPOSTDELETE', 'Прокси к событию PHP OnSocNetLogDelete', 'Живая лента'),
('ONLIVEFEEDPOSTUPDATE', 'Прокси к событию PHP OnAfterSocNetLogUpdate.', 'Живая лента'),
('OnSaleBeforeOrderDelete', 'Вызывается перед удалением заказа.', 'Интернет-магазин'),
('OnSaleOrderSaved', 'Происходит в конце сохранения заказа, когда заказ и все связанные сущности уже сохранены.', 'Интернет-магазин'),
('ONSONETGROUPADD', '', 'Рабочие группы соцсети'),
('ONSONETGROUPDELETE', 'Вызывается в момент удаления рабочей группы. Прокси к событию OnSocNetGroupDelete', 'Рабочие группы соцсети'),
('ONSONETGROUPSUBJECTADD', 'Событие вызывается после создания темы рабочих групп. Прокси к событию OnSocNetGroupSubjectAdd.', 'Рабочие группы соцсети'),
('ONSONETGROUPSUBJECTDELETE', 'Вызывается перед удалением темы рабочих групп.. Прокси к событию OnSocNetGroupSubjectDelete', 'Рабочие группы соцсети'),
('ONSONETGROUPSUBJECTUPDATE', 'Событие вызывается после изменения темы рабочих групп. Прокси к событию OnSocNetGroupSubjectUpdate', 'Рабочие группы соцсети'),
('ONSONETGROUPUPDATE', 'Событие вызывается после изменения рабочей группы. Прокси к событию onSocnetGroupUpdate', 'Рабочие группы соцсети'),
('OnTaskAdd', 'при добавлении задачи.', 'Задачи'),
('OnTaskCommentAdd', 'при добавлении комментария к задаче.', 'Задачи'),
('OnTaskCommentUpdate', 'при проведении операций над комментарием к задаче.', 'Задачи'),
('OnTaskDelete', 'при удалении задачи.', 'Задачи'),
('OnTaskUpdate', 'при обновлении задачи.', 'Задачи'),
('OnUserAdd', 'при добавлении пользователя в Битрикс24', 'Портал');

--
-- Дамп данных таблицы `admin_chatbot_app_contex_directory`
--

INSERT INTO `admin_chatbot_app_contex_directory` (`code`, `title`) VALUES
('ALL', 'приложение будет доступно во всех чатах'),
('BOT', 'приложение будет доступно только у чат-бота, который установил приложение'),
('CALL', 'приложение будет доступно только в чатах, созданных в рамках Телефонии'),
('CHAT', 'приложение будет доступно только в групповых чатах'),
('LINES', 'приложение будет доступно только в чатах Открытых линий'),
('USER', 'приложение будет доступно только в чатах Один-на-один');



--
-- Дамп данных таблицы `admin_chatbot_app_js_method_directory`
--

INSERT INTO `admin_chatbot_app_js_method_directory` (`code`, `title`) VALUES
('CALL', 'вызов телефонного номера'),
('PUT', 'вставка команды чат-боту в textarea'),
('SEND', 'отправка команды чат-боту'),
('SUPPORT', 'открытие чат-бота техподдержки работающего через Открытые линии');



--
-- Дамп данных таблицы `admin_chatbot_color_directory`
--

INSERT INTO `admin_chatbot_color_directory` (`name`, `title`) VALUES
('AQUA', 'Аква'),
('AZURE', 'Лазурный'),
('BROWN', 'Коричневый'),
('DARK_BLUE', 'Синий'),
('GRAPHITE', 'Графитовый'),
('GRAY', 'Серый'),
('GREEN', 'Зелёный'),
('KHAKI', 'Хаки'),
('LIGHT_BLUE', 'Голубой'),
('LIME', 'Лайм'),
('MARENGO', 'Маренго'),
('MINT', 'Мятный'),
('PINK', 'Розовый'),
('PURPLE', 'Фиолетовый'),
('RED', 'Красный'),
('SAND', 'Песочный');



--
-- Дамп данных таблицы `admin_chatbot_type_directory`
--

INSERT INTO `admin_chatbot_type_directory` (`name`, `title`) VALUES
('B', 'чат-бот, ответы поступают сразу'),
('H', 'человек, ответы поступают с задержкой от 2-х до 10 секунд'),
('O', 'чат-бот для Открытых линий'),
('S', 'чат-бот с повышенными привилегиями (supervisor)');

--
-- Дамп данных таблицы `admin_agents`
--

INSERT INTO `admin_agents` (`id`, `name`, `class`, `method`, `params`, `date_run`, `period`, `status_id`) VALUES
(1, 'События', 'wm\\admin\\models\\settings\\events\\Events', 'getOflineEventsHendlersRun', '-', '2021-01-01 00:00:00', 55, 1);


--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin_b24portal`
--
ALTER TABLE `admin_b24portal`
  ADD PRIMARY KEY (`PORTAL`);

--
-- Индексы таблицы `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`name_id`);

--
-- Индексы таблицы `admin_b24_connect_settings`
--
ALTER TABLE `admin_b24_connect_settings`
  ADD PRIMARY KEY (`name_id`);
  
  --
-- Индексы таблицы `admin_robots`
--
ALTER TABLE `admin_robots`
  ADD PRIMARY KEY (`code`);

--
-- Индексы таблицы `admin_robots_options`
--
ALTER TABLE `admin_robots_options`
  ADD PRIMARY KEY (`property_name`,`robot_code`,`value`),
  ADD KEY `admin_robots_options_fk0` (`robot_code`,`property_name`);

--
-- Индексы таблицы `admin_robots_properties`
--
ALTER TABLE `admin_robots_properties`
  ADD PRIMARY KEY (`robot_code`,`system_name`),
  ADD KEY `admin_robots_properties_fk1` (`type_id`);

--
-- Индексы таблицы `admin_robots_types`
--
ALTER TABLE `admin_robots_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `admin_events`
--
ALTER TABLE `admin_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_events_fk0` (`event_name`);

--
-- Индексы таблицы `admin_events_directory`
--
ALTER TABLE `admin_events_directory`
  ADD PRIMARY KEY (`name`);

--
-- Индексы таблицы `admin_placement`
--
ALTER TABLE `admin_placement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `placement_name` (`placement_name`);

--
-- Индексы таблицы `admin_placement_directory`
--
ALTER TABLE `admin_placement_directory`
  ADD PRIMARY KEY (`name_id`);

--
-- Индексы таблицы `admin_chatbot`
--
ALTER TABLE `admin_chatbot`
  ADD PRIMARY KEY (`code`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `p_color_name` (`p_color_name`);

--
-- Индексы таблицы `admin_chatbot_app`
--
ALTER TABLE `admin_chatbot_app`
  ADD UNIQUE KEY `bot_code` (`bot_code`,`code`),
  ADD KEY `contex_code` (`contex_code`),
  ADD KEY `js_method_code` (`js_method_code`);

--
-- Индексы таблицы `admin_chatbot_app_contex_directory`
--
ALTER TABLE `admin_chatbot_app_contex_directory`
  ADD PRIMARY KEY (`code`);

--
-- Индексы таблицы `admin_chatbot_app_js_method_directory`
--
ALTER TABLE `admin_chatbot_app_js_method_directory`
  ADD PRIMARY KEY (`code`);

--
-- Индексы таблицы `admin_chatbot_color_directory`
--
ALTER TABLE `admin_chatbot_color_directory`
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `admin_chatbot_command`
--
ALTER TABLE `admin_chatbot_command`
  ADD UNIQUE KEY `bot_code_command` (`bot_code`,`command`);

--
-- Индексы таблицы `admin_chatbot_type_directory`
--
ALTER TABLE `admin_chatbot_type_directory`
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `admin_dg_templates`
--
ALTER TABLE `admin_dg_templates`
  ADD UNIQUE KEY `code` (`code`);

--
-- Индексы таблицы `admin_agents`
--
ALTER TABLE `admin_agents`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin_robots_types`
--
ALTER TABLE `admin_robots_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `admin_events`
--
ALTER TABLE `admin_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `admin_placement`
--
ALTER TABLE `admin_placement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `admin_agents`
--
ALTER TABLE `admin_agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `admin_robots_options`
--
ALTER TABLE `admin_robots_options`
  ADD CONSTRAINT `admin_robots_options_fk0` FOREIGN KEY (`robot_code`,`property_name`) REFERENCES `admin_robots_properties` (`robot_code`, `system_name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `admin_robots_properties`
--
ALTER TABLE `admin_robots_properties`
  ADD CONSTRAINT `admin_robots_properties_fk0` FOREIGN KEY (`robot_code`) REFERENCES `admin_robots` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_robots_properties_fk1` FOREIGN KEY (`type_id`) REFERENCES `admin_robots_types` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `admin_events`
--
ALTER TABLE `admin_events`
  ADD CONSTRAINT `admin_events_fk0` FOREIGN KEY (`event_name`) REFERENCES `admin_events_directory` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `admin_placement`
--
ALTER TABLE `admin_placement`
  ADD CONSTRAINT `admin_placement_ibfk_1` FOREIGN KEY (`placement_name`) REFERENCES `admin_placement_directory` (`name_id`);

--
-- Ограничения внешнего ключа таблицы `admin_chatbot`
--
ALTER TABLE `admin_chatbot`
  ADD CONSTRAINT `admin_chatbot_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `admin_chatbot_type_directory` (`name`),
  ADD CONSTRAINT `admin_chatbot_ibfk_2` FOREIGN KEY (`p_color_name`) REFERENCES `admin_chatbot_color_directory` (`name`);

--
-- Ограничения внешнего ключа таблицы `admin_chatbot_app`
--
ALTER TABLE `admin_chatbot_app`
  ADD CONSTRAINT `admin_chatbot_app_ibfk_1` FOREIGN KEY (`contex_code`) REFERENCES `admin_chatbot_app_contex_directory` (`code`),
  ADD CONSTRAINT `admin_chatbot_app_ibfk_2` FOREIGN KEY (`js_method_code`) REFERENCES `admin_chatbot_app_js_method_directory` (`code`),
  ADD CONSTRAINT `admin_chatbot_app_ibfk_3` FOREIGN KEY (`bot_code`) REFERENCES `admin_chatbot` (`code`);

--
-- Ограничения внешнего ключа таблицы `admin_chatbot_command`
--
ALTER TABLE `admin_chatbot_command`
  ADD CONSTRAINT `admin_chatbot_command_ibfk_1` FOREIGN KEY (`bot_code`) REFERENCES `admin_chatbot` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;