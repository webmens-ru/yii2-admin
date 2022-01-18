<?php

namespace wm\admin\controllers\ui\menu;

use wm\admin\models\ui\menu\MenuItemSearch;
use wm\admin\models\ui\menu\MenuItem;
use Yii;

/**
 * Class MenuItemController
 * @package wm\admin\controllers\ui\menu
 */
class MenuItemController extends \wm\admin\controllers\ActiveRestController {

    /**
     * @var string
     */
    public $modelClass = MenuItem::class;
    /**
     * @var string
     */
    public $modelClassSearch = MenuItemSearch::class;

    /**
     * @param $menuId
     * @return array
     */
    public function actionItems($menuId) {
        $userId = Yii::$app->user->id;
        $model = MenuItem::getItems($menuId, $userId);
        return $model;
    }

    /**
     * @return mixed
     */
    public function actionSaveItems() {

        Yii::warning('actionSaveItems','action');
        
        //$request = Yii::$app->request;
        //$name = json_decode($request->post());
		
		Yii::warning(Yii::$app->getRequest()->getBodyParams());
		$name = Yii::$app->getRequest()->getBodyParams(); 
		
//        
//        $name = isset($_POST['name']) ? $_POST['name'] : null;

       return $name;
    }
}











        
        // эквивалентно: 
//        $name = isset($_POST['id']) ? $_POST['name'] : null;
       
//    $request=Yii::$app->getRequest();
//    $myData= $request->bodyParams['myData'];
    

        
        
//        $data=[
//            'id'=>26,
//        ];
//        return $this->asJson($data);
        //return $this->asJson($name);
        //return true;
