<?php

namespace wm\admin\controllers\ui\filter;

use wm\admin\models\ui\filter\FilterItemSearch;
use wm\admin\models\ui\filter\FilterItem;
use Yii;

class FilterItemController extends \wm\admin\controllers\ActiveRestController {

    public $modelClass = FilterItem::class;
    public $modelClassSearch = FilterItemSearch::class;
	
	public function actionItems($menuId) {
        $userId = Yii::$app->user->id;
        $model = FilterItem::getItems($menuId, $userId);
        return $model;
    }

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
