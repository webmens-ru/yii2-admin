<?php

use yii\db\Migration;

class m211204_203800_cteate_chat_bot_init extends Migration
{
    public function up()
    {
        //admin_chatbot_type_directory
        $this->createTable('{{%admin_chatbot_type_directory%}}', [
            'name' => $this->char(1)->notNull(),
            'title' => $this->string(255)->notNull(),
        ]);
        $this->batchInsert(
            'admin_chatbot_type_directory',
            [
                'name',
                'title',
            ],
            [
                ['B', 'чат-бот, ответы поступают сразу'],
                ['H', 'человек, ответы поступают с задержкой от 2-х до 10 секунд'],
                ['O', 'чат-бот для Открытых линий'],
                ['S', 'чат-бот с повышенными привилегиями (supervisor)'],
            ]
        );
        $this->addPrimaryKey('name', 'admin_chatbot_type_directory', 'name');

        //admin_chatbot_app_contex_directory
        $this->createTable('{{%admin_chatbot_app_contex_directory%}}', [
            'code' => $this->string(32)->notNull(),
            'title' => $this->string(255)->notNull(),
        ]);
        $this->batchInsert(
            'admin_chatbot_app_contex_directory',
            [
                'code',
                'title',
            ],
            [
                ['ALL', 'приложение будет доступно во всех чатах'],
                ['BOT', 'приложение будет доступно только у чат-бота, который установил приложение'],
                ['CALL', 'приложение будет доступно только в чатах, созданных в рамках Телефонии'],
                ['CHAT', 'приложение будет доступно только в групповых чатах'],
                ['LINES', 'приложение будет доступно только в чатах Открытых линий'],
                ['USER', 'приложение будет доступно только в чатах Один-на-один'],
            ]
        );
        $this->addPrimaryKey('code', 'admin_chatbot_app_contex_directory', 'code');

        //admin_chatbot_color_directory
        $this->createTable('{{%admin_chatbot_color_directory%}}', [
            'name' => $this->string(20)->notNull(),
            'title' => $this->string(255)->notNull(),
        ]);
        $this->batchInsert(
            'admin_chatbot_color_directory',
            [
                'name',
                'title',
            ],
            [
                ['AQUA', 'Аква'],
                ['AZURE', 'Лазурный'],
                ['BROWN', 'Коричневый'],
                ['DARK_BLUE', 'Синий'],
                ['GRAPHITE', 'Графитовый'],
                ['GRAY', 'Серый'],
                ['GREEN', 'Зелёный'],
                ['KHAKI', 'Хаки'],
                ['LIGHT_BLUE', 'Голубой'],
                ['LIME', 'Лайм'],
                ['MARENGO', 'Маренго'],
                ['MINT', 'Мятный'],
                ['PINK', 'Розовый'],
                ['PURPLE', 'Фиолетовый'],
                ['RED', 'Красный'],
                ['SAND', 'Песочный'],
            ]
        );
        $this->addPrimaryKey('name', 'admin_chatbot_color_directory', 'name');

        //admin_chatbot_app_js_method_directory
        $this->createTable('{{%admin_chatbot_app_js_method_directory%}}', [
            'code' => $this->string(32)->notNull(),
            'title' => $this->string(255)->notNull(),
        ]);
        $this->batchInsert(
            'admin_chatbot_app_js_method_directory',
            [
                'code',
                'title',
            ],
            [
                ['CALL', 'вызов телефонного номера'],
                ['PUT', 'вставка команды чат-боту в textarea'],
                ['SEND', 'отправка команды чат-боту'],
                ['SUPPORT', 'открытие чат-бота техподдержки работающего через Открытые линии'],
            ]
        );
        $this->addPrimaryKey('code', 'admin_chatbot_app_js_method_directory', 'code');

        //admin_chatbot
        $this->createTable('{{%admin_chatbot%}}', [
            'code' => $this->string(64)->notNull(),
            'type_id' => $this->char(1)->notNull(),
            'openline' => $this->char(1)->null(),
            'p_name' => $this->string(255)->notNull(),
            'p_last_name' => $this->string(255)->notNull(),
            'p_color_name' => $this->string(20)->notNull(),
            'p_email' => $this->string(255)->notNull(),
            'p_personal_birthday' => $this->date()->null(),
            'p_work_position' => $this->string(255)->null(),
            'p_personal_www' => $this->string(255)->null(),
            'p_personal_gender' => $this->tinyInteger(1)->null(),
            'p_personal_photo_url' => $this->string(255)->null(),
            'event_handler' => $this->string(255)->null(),
            'event_massege_add' => $this->string(255)->null(),
            'event_massege_update' => $this->string(255)->null(),
            'event_massege_delete' => $this->string(255)->null(),
            'event_welcome_massege' => $this->string(255)->null(),
            'event_bot_delete' => $this->string(255)->null(),
            'bot_id' => $this->integer()->null(),
        ]);
        $this->addPrimaryKey('code', 'admin_chatbot', 'code');
        $this->addForeignKey('admin_chatbot_ibfk_0', 'admin_chatbot', ['type_id'], 'admin_chatbot_type_directory', ['name']);
        $this->addForeignKey('admin_chatbot_ibfk_1', 'admin_chatbot', ['p_color_name'], 'admin_chatbot_color_directory', ['name']);

        //admin_chatbot_command
        $this->createTable('{{%admin_chatbot_command%}}', [
            'bot_code' => $this->string(64)->notNull(),
            'command' => $this->string(255)->notNull(),
            'common' => $this->char(1)->notNull(),
            'hidden' => $this->char(1)->notNull(),
            'extranet_support' => $this->char(1)->notNull(),
            'title_ru' => $this->string(255)->notNull(),
            'params_ru' => $this->string(255)->notNull(),
            'title_en' => $this->string(255)->notNull(),
            'params_en' => $this->string(255)->notNull(),
            'event_command_add' => $this->string(255)->notNull(),
            'command_id' => $this->integer()->null(),
        ]);
        $this->addPrimaryKey('code', 'admin_chatbot_command', ['bot_code','command']);
        $this->addForeignKey('admin_chatbot_command_ibfk_0', 'admin_chatbot_command', ['bot_code'], 'admin_chatbot', ['code'], 'CASCADE', 'CASCADE');

        //admin_chatbot_command
        $this->createTable('{{%admin_chatbot_app%}}', [
            'bot_code' => $this->string(64)->notNull(),
            'code' => $this->string(32)->notNull(),
            'js_method_code' => $this->string(32)->null(),
            'js_param' => $this->string(32)->null(),
            'icon_file' => $this->text()->null(),
            'contex_code' => $this->string(32)->notNull(),
            'extranet_support' => $this->char(1)->notNull(),
            'iframe_popup' => $this->char(1)->notNull(),
            'title_ru' => $this->string(255)->notNull(),
            'title_en' => $this->string(255)->notNull(),
            'iframe' => $this->string(255)->null(),
            'iframe_height' => $this->integer()->null(),
            'iframe_width' => $this->integer()->null(),
            'hash' => $this->string(32)->null(),
            'hidden' => $this->char(1)->null(),
            'livechat_support' => $this->char(1)->notNull(),
            'type' => $this->string(32)->notNull(),
            'app_id' => $this->integer()->null(),
        ]);
        $this->addPrimaryKey('code', 'admin_chatbot_app', ['bot_code','code']);
        $this->addForeignKey('admin_chatbot_app_ibfk_0', 'admin_chatbot_app', ['contex_code'], 'admin_chatbot_app_contex_directory', ['code']);
        $this->addForeignKey('admin_chatbot_app_ibfk_1', 'admin_chatbot_app', ['js_method_code'], 'admin_chatbot_app_js_method_directory', ['code']);
        $this->addForeignKey('admin_chatbot_app_ibfk_2', 'admin_chatbot_app', ['bot_code'], 'admin_chatbot', ['code']);
    }

    public function down()
    {
        $this->dropTable('{{%admin_chatbot_app%}}');
        $this->dropTable('{{%admin_chatbot_command%}}');
        $this->dropTable('{{%admin_chatbot%}}');
        $this->dropTable('{{%admin_chatbot_app_js_method_directory%}}');
        $this->dropTable('{{%admin_chatbot_color_directory%}}');
        $this->dropTable('{{%admin_chatbot_app_contex_directory%}}');
        $this->dropTable('{{%admin_chatbot_type_directory%}}');
    }
}
