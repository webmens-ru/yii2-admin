<?php

use yii\db\Migration;

class m211207_000400_cteate_grid_init extends Migration {

    public function up() {

        $this->createTable('{{%admin_grid_column%}}', [
            'id' => $this->primaryKey(),
            'entityCode' => $this->string(64)->notNull(),
            'code' => $this->string(32)->notNull(),
            'title' => $this->string(255)->notNull(),
            'visible' => $this->tinyInteger(1)->notNull(),
            'order' => $this->integer()->notNull(),
            'width' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('grid_column_fk0', 'admin_grid_column', ['entityCode'], 'admin_entity', ['code']);

        
        $this->createTable('{{%admin_grid_column_personal%}}', [
            'id' => $this->primaryKey(),
            'columnId' => $this->integer()->notNull(),
            'userId' => $this->integer()->notNull(),
            'order' => $this->integer()->notNull(),
            'visible' => $this->tinyInteger(1)->notNull(),
            'width' => $this->integer()->notNull(),            
        ]);

        $this->addForeignKey('grid_column_personal_fk0', 'admin_grid_column_personal', ['columnId'], 'admin_grid_column', ['id'], 'CASCADE', 'CASCADE');

    }

    public function down() {
       $this->dropTable('{{%admin_grid_column_personal%}}');
        $this->dropTable('{{%admin_grid_column%}}');
    }

}
