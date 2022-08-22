<?php

class m220818_134900_admin_menu_item_type_init extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_menu_item_type%}}', [
            'code' => $this->string(32)->notNull(),
            'title' => $this->string(255)->notNull()
        ]);

        $this->addPrimaryKey('code', 'admin_menu_item_type', 'code');

        $this->batchInsert(
            'admin_menu_item_type',
            [
                'code',
                'title'
            ],
            [
                ['updatePage', 'Обновление текущей страницы'],
                ['openLink', 'Открытие вкладки в браузере'],
                ['openApplication', 'Открытие слайдера'],
            ]
        );

        $this->addForeignKey('admin_menu_item_ibfk_2', 'admin_menu_item', ['type'], 'admin_menu_item_type', ['code'], 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%admin_menu_item_type%}}');
    }
}
