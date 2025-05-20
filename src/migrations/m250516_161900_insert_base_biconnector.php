<?php

class m250516_161900_insert_base_biconnector extends \yii\db\Migration
{
    public function up()
    {
        $siteUrl = \yii\helpers\ArrayHelper::getValue(Yii::$app->params, 'siteUrl')?:'';
        $this->batchInsert(
            'admin_biconnector',
            [
                'title',
                'logo',
                'description',
                'urlCheck',
                'urlTableList',
                'urlTableDescription',
                'urlData',
                'sort',
                'bx24Id',
                'isSystem',
            ],
            [
                [
                    'WebMens connector',
                    $siteUrl.'/img/logo.png',
                    '',
                    $siteUrl.'/admin/handlers/biconnector/check',
                    $siteUrl.'/admin/handlers/biconnector/table-list',
                    $siteUrl.'/admin/handlers/biconnector/table-description',
                    $siteUrl.'/admin/handlers/biconnector/data',
                    100,
                    null,
                    1,
                ],
            ]
        );
    }

    public function down()
    {
    }
}
