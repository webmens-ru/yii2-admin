<?php

use yii\db\Migration;

class m211206_233700_cteate_menu_init extends Migration {

    public function up() {

        $this->createTable('{{%admin_menu%}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);

        $this->batchInsert('admin_menu',
                [
                    'id',
                    'name'
                ],
                [
                    [1, 'main']
                ]
        );

        $this->createTable('{{%admin_menu_item%}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'visible' => $this->tinyInteger(1)->notNull(),
            'order' => $this->integer()->notNull(),
            'params' => $this->string(255)->notNull(),
            'menuId' => $this->integer()->notNull(),
            'type' => $this->string(32)->notNull(),
        ]);

        $this->batchInsert('admin_menu_item',
                [
                    'id',
                    'title',
                    'visible',
                    'order',
                    'params',
                    'menuId',
                    'type'
                ],
                [
                    [1, 'Основная', 1, 1, '{}', 1, 'updatePage'],
                    [2, 'Админка', 0, 2, '{"url":"https://webmens.ru/admin"}', 1, 'openLink'],
                ]
        );

        $this->addForeignKey('admin_menu_item_ibfk_1', 'admin_menu_item', ['menuId'], 'admin_menu', ['id'], 'CASCADE', 'CASCADE');

        $this->createTable('{{%admin_menu_item_personal%}}', [
            'id' => $this->primaryKey(),
            'itemId' => $this->integer()->notNull(),
            'userId' => $this->integer()->notNull(),
            'order' => $this->integer()->notNull(),
            'visible' => $this->tinyInteger(1)->notNull(),
        ]);
        $this->addForeignKey('menu_item_personal_fk0', 'admin_menu_item_personal', ['itemId'], 'admin_menu_item', ['id'], 'CASCADE', 'CASCADE');
    }

    public function down() {
        $this->dropTable('{{%admin_menu_item_personal%}}');
        $this->dropTable('{{%admin_menu_item%}}');
        $this->dropTable('{{%admin_menu%}}');
    }

}
