<?php

class m220920_102000_create_grid_column_type extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('{{%admin_grid_column_type%}}', [
            'code' => $this->string(32)->notNull(),
            'title' => $this->string(255)->notNull()
        ]);

        $this->addPrimaryKey('code', 'admin_grid_column_type', 'code');

        $this->batchInsert(
            'admin_grid_column_type',
            [
                'code',
                'title'
            ],
            [
                ['string', 'Строка'],
                ['number', 'Число'],
                ['date', 'Дата'],
                ['link', 'Ссылка'],
            ]
        );

        $this->addForeignKey('admin_grid_column_type_ibfk_2', 'admin_grid_column', ['type'], 'admin_grid_column_type', ['code'], 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%admin_grid_column_type%}}');
    }
}
