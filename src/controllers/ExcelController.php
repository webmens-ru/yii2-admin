<?php

namespace wm\admin\controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ExcelController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost:3000'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Method' => ['POST', 'PUT', 'PATCH', 'DELETE', 'GET', 'OPTIONS'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Request-Headers' => ['X-Wsse'],
                    // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                    'Access-Control-Allow-Origin' => ['*'],
                    'Access-Control-Allow-Headers' => ['*'],
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    \wm\yii\filters\auth\HttpBearerAuth::className(),
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public static function actionGetExcel()
    {
        $requestArr = Yii::$app->getRequest()->getBodyParams();
        $data = self::prepareDate($requestArr);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()
            ->fromArray(
                $data,
                NULL,
                'A1'
            );
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $file = ob_get_contents();
        ob_clean();
        return $file;
    }

    public static function prepareDate($data){
        $result = [];
        foreach ($data as $row){
            $tempRow = [];
            foreach ($row as $key=> $value){
                $tempRow[$key] =  is_array($value)?ArrayHelper::getValue($value, 'title'):$value;

            }
            $result[] = $tempRow;
        }
        return $result;
    }
}