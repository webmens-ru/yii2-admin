- feat: Добавлена возможность переименовывать entity
- feat: Добавлена поддержка типа поля image
- feat: Добавлена синхронизация календарей пользователей
- fix: Удалены ненужные комментарии
- Merge pull request #32 from apfursa/master
- fix: исправлена ошибка выбора полей задач
- feat: Добавлена синхронизация задач
- Merge pull request #31 from RashnFlow/master
- fix: phpstan=5 ошибок нет
- fix: phpstan 3+ & phpcs
- fix(User): add phpDocBlock
- Merge branch 'webmens-ru:master' into master
- feat: Клиентское редактирование грида
- fix: Исправление ошибок найденых при анализе phpstan=0 и небольшой рефакторинг
- fix: codeStyle
- Merge pull request #30 from apfursa/master
- Merge branch 'webmens-ru:master' into master
- fix: Исправили ошибку падения из-за отсутствия доступа на запись
- Merge remote-tracking branch 'origin/master'
- fix: Вернули ошибочно удалённый файл.
- Merge pull request #29 from RashnFlow/master
- feat: Колонка в b2portal для удаления приложения
- fix: В модуле генерации документов исправили ошибку Title
- fix: Удалили генераторы таблиц, так как они не нужны после появления синхронизатора
- fix: Удалили не актуальные файлы для синхронизации пользователей и статусов
- fix: Исправлена ошибка возникавшая при переустановке действия(команды) чат бота
- fix: удалили ненужные use B24ConnectSettings
- fix: Все вызовы connect заменены на connectFromAdmin и connectFromUser
- feat: Добавлена функция получения имени портала из авторизационных данных пользователя
- fix: Исправлена ошибка синхронизации вспомогательных сущностей
- fix: Исправлена ошибка синхронизации Сотрудников
- feat: добавлена поддержка синхронизации направлений и вспомогательных сущностей
- fix: Исправлена ошибка что options не возвращали
- Revert "fix: Изменили поля типа json на text в БД для поддержки MySQL ниже 5.7"
- fix: Изменили поля типа json на text в БД для поддержки MySQL ниже 5.7
- Merge pull request #28 from RashnFlow/master
- fix: options->json (filter_field)
- fix: Перескакивание на час в агентах
- Merge pull request #27 from RashnFlow/master
- fix: Отображение dropdown
- feat: добавлена поддержка gridOptions и header
- Merge pull request #26 from RashnFlow/master
- feat: buttonAdd и Действия в ActiveRest
- Merge pull request #25 from RashnFlow/master
- fix: Deprecated GridAction
- feat: Добавлена синхронизация задач
- Merge pull request #23 from RashnFlow/master
- Merge pull request #22 from apfursa/master
- fix: Грамматическая ошибка
- fix: Убран warning в Placement
- feat: Добавление новых событий
- feat: добавлена синхронизация смарт-процессов
- Merge pull request #21 from RashnFlow/master
- fix: Сохранение пунктов меню
- Merge pull request #19 from RashnFlow/master
- fix: Возвращение bool-типа
- Merge pull request #16 from RashnFlow/master
- feat: Добавление 'description' в таблицу admin_events_directory для ONSONETGROUPADD
- Merge pull request #15 from RashnFlow/master
- Merge pull request #14 from RashnFlow/master
- fix: Лишние импорты, отображение в пункте "Список установленных строек"
- Merge remote-tracking branch 'origin/master'
- feat: Добавление поддержки встройки в смарт-процесс
- feat: Миграции для встройки в смарт-процесс
- Merge branch 'webmens-ru:master' into master
- fix: Исправлена ошибка отображения списка установленных встроек
- fix: Вьюхи приведены в соответствие модели
- fix: Исправлена ошибка проверки данных в агентах
- fix: Исправлена ошибка задвоения Лидов и сделок при синхронизации
- feat: Таблица с типами для grid_column
- Merge remote-tracking branch 'origin/master'
- Merge pull request #13 from RashnFlow/master
- feat: В вывод информации о поле фильтра добавлена информация filtrationType
- fix: Удалено не используемое поле title в filter_field_settings
- fix: исправлена ошибка периодичности
- feat: Добавлены параметры для определения типа фильтрации server & client и options для задания списка выпадающих элементов.
- fix: Исправлена ошибка подготовки данных для построения списка полей синхронизации Emploes
- fix: Исправление названия миграции и добавление null
- feat: Добавление поля params для entity
- fix: Исправлена механика создания первого поля таблицы
- fix: Исправлена ошибка формата возвращаемых данных для списка полей
- feat: Добавлена поддержка синхронизации справочников
- fix: Исправление ошибок синхронизации Телефонии
- fix: Изменена длинна строк в БД были случаи когда не влазили. feat: Добавлена возможность множественного выбора полей
- fix: Исправление ммиграции params
- fix(ui/grid column): Исправлена ошибка с получением данных и валидацией
- feat(synchronization): Добавлена миграция выпущенных синхронизаций
- fix(Agents): Исправлена ошибка в rules
- fix: Исправлен параметр периодичности синхронизации
- fix: Исправлена ошибка расположения папки с виджетами
- Merge branch 'cync_new_entity'
- Merge branch 'sync_update_agent'
- Merge branch 'agent_widget'
- Merge branch 'Agents'
- Merge pull request #12 from RashnFlow/master
- feat: расширение настройки времени выполнения у агентов
- fix: Незначительные правки code style и удаление не нужных warning
- feat: add new entity
- fix: Добавлены сценарии и rules для модели агента
- feat: Добавлена поддержка обновлённых агентов
- feat: Создан виджет для форм(выбор параметров времени запуска агента)
- Merge pull request #11 from RashnFlow/master
- fix
- feat(Synchronization): Добавлена миграция, удалён неиспользуемый код, небольшие улучшения.
- feat: Добавление "Сценарий встройки WebRtc" в placement_directory
- feat(17938): Изменить тип поля params в таблице admin_grid_action на text
- fix: Название внешнего ключа
- Merge pull request #10 from RashnFlow/master
- Merge branch 'synchronization'
- feat: Разработан модуль синхронизации
- feat(17358) : Таблица с типами для admin_menu_item
- feat and fix: Возвращение bool-типа и миграции
- fix(11798): Исправить ссылку на домашнюю страницу в админке
- feat: Добавление полей для нового грида
- fix: Исправлена ошибка отсутствия зависимостей
- fix(ui/grid/Action): Исправлена ошибка формата возвращаемых данных
- feat: Добавлен столбец type в grid_column для совместимости с новым гридом
- fix: Исправлен title главной страницы админки
- fix: update bootstrap5
- fix: Обновили версию Бутстрап до 5
- fix: Код приведен в соответствие PSR-12
- Merge branch 'codestyle'
- fix: Исправлен CodeStyle
- +
- fix(composer): Устранена ошибка отсутствия phpOffice
- fix(): string->safe
- fix: Исправлена ошибкав трейде
- Merge branch 'SP_Table_generate'
- add controllers
- add models
- Добавлены пункты меню
- add view
- fix: Удалены файлы которых не должно быть в админке
- fix(Excell): Организована поддержка 2-х форматов входных данных
- fix(Excel): Исправлена ошибка определения имени столбца
- feat(Excell): Добавлена граница ячеек и определение количества столбцов
- Merge pull request #9 from RashnFlow/master
- feat: Добавлен функционал: - Установки стилей для ячеек - Установка автоматической ширины для стоблцов
- +
- fix: kartik-v/yii2-widget-fileinput
- fix: minimum-stability
- feth(ExcellController): добавлена конвертация данных перед выводом в Excel.
- fix: Исправлена дата в миграции
- Merge branch 'develop'
- fix: Исправлена ошибка даты в миграции
- fix: исправлена ошибка в ключе filter_field_setting_fk0
- fix: Удалён ненужный агент
- Merge pull request #6 from RashnFlow/master
- fix: Исправления генератора форм: - Исправлено имя с "forms" на "form" - В функции fields исправлено возвращаемое булевое значение
- Merge pull request #5 from RashnFlow/master
- fix: Исправления по формам: - Изменен баг в миграции связанный с удлаением внешнего ключа - В моделях убран primary key "id" из rules, а также добавлено правило "safe" для некоторых полей
- feat: Добавлена документация
- feat: Генератор форм
- fix: Убраны файлы по генератору форм
- feat and fix: Добвалены следующие изменения: - Добавлена новая миграция и модель "UserSettings" - В миграциях Добавлены NOT NULL поля - Добавлен метод для поиска пользовательских настроек
- feat and fix: Произведены следующие дйествия: - Доабвлены модели для работы с формами - Убран класс миграции - В моеделе "CrmUser" отредактировано описание PHPDoc
- feat: Добавлены миграции по формам
- Merge pull request #3 from RashnFlow/master
- feat: Добавлена поддержка Excel
- fix: Исправлен баг последнего комита
- fix(FilterFildSettingsController): Добавлена документация
- fix(FilterController): Добавлена документация
- fix: add base PHPDoc
- feat: Изменено поле селект со стороны фронта. Выполнена поддержка передаваемых параметров с нашей стороны.
- fix: Изменено имя свойства title -> label
- fix: Удалены мусорные комментарии и варнинги нужные варнинги переписаны на error
- fix(CrmUser): Исправлена ошибка правил валидации поля ID
- Merge pull request #2 from RashnFlow/master
- feat: Добавлена поддержка сущности "Пользователь"
- feat: Добавлена миграция для запуска синхронизации справочников чере механизм агентов
- Merge pull request #1 from RashnFlow/master
- feat: Добавлено grid/action
- feat: Добавлена синхронизация сущности "Справочник"
- feat: Добавление миграции и модели сущности "Справочник"
- fix(migration): Исправлена ошибка неправильного PrimaryKey
- feat: Добавлены новые события в справочники
- fix: rest+cgi+HttpBearerAuth
- add migration add_data_filter_field_type
- +
- Исправлены ошибки по экспорту и импорту роботов без потери обратной совместимости.
- +
- +
- +
- +
- +
- add comment, delete warning
- update url hendler
- +
- fix bug chekbox
- +
- fix bug delete file robot
- fix bag
- +
- Исправлены ошибки путей к хендлерам
- update err
- migrate auth_assignment data
- Исправлена ошибка связей таблиц
- Исправлена ошибка с данными
- +
- +
- auth migrate
- Удаление ненужных файлов
- исправлена ошибка
- ui migrations
- Merge remote-tracking branch 'origin/master'
- +
- 32-64
- 32->64
- 32-64
- +
- Merge remote-tracking branch 'origin/master'
- entity migrate
- doc generator migrate
- chat bot migrate
- +
- agents migrate
- placement migrate
- +
- events migrate
- +
- robots migrate
- +
- migrate
- users
- user migrate init
- Изменен путь до контроллера чатбот команд
- login - logout
- +
- Исправлена ошибка вызова несуществующего метода
- +
- +
- +
- add RestController
- Update placement v2->v3
- Edite base url
- +
- Добавил строку $model->userId = $userId;
- изменил  в строке 70 на ServerErrorHttpException
- 1
- +
- public function actionSchema($entity = null)
- Entity
- +
- UI Create
- +
- Изменил правила проверки доступа
- добавил слово static
- +
- +
- Исправлена ошибка в структуре данных admin_robots_types
- edit export and import
- +
- +
- +
- +
- add Handlers
- +
- edit name space wm\b24tools\b24Tools
- add access
- edit style
- +
- исправлена ошибка в настройках прав доступа
- +
- add access filter
- add baseModuleController
- +
- +
- +
- +
- +
- +
- +
- +
- +
- +
- +
- +
- +
- +
- edit assets
- edit Aliases
- start
- Initial commit
