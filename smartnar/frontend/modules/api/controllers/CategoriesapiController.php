<?php
namespace frontend\modules\api\controllers;

use Yii;
use yii\base\InvalidParamException;categoriesapi
use yii\web\BadRequestHttpException;
use yii\rest\ActiveController;
use yii\web\Response;
use common\models\CategoriesSearch; 
use common\models\Categories; 
use common\models\Sliders;
use yii\db\Expression;

/**
 * Site controller
 */
class CategoriesapiController extends ActiveController
{
    public $modelClass = "common\models\Categories";


public function behaviors()
{
    $behaviors = parent::behaviors();
    $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
    

     // remove authentication filter
    $auth = $behaviors['authenticator'];
    unset($behaviors['authenticator']);
    // add CORS filter
    $behaviors['corsFilter'] = [
        'class' => \yii\filters\Cors::className(),
    ];
    // re-add authentication filter
    $behaviors['authenticator'] = $auth;
    // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
    $behaviors['authenticator']['except'] = ['options'];
    return $behaviors;
}


   public function actionList()
    {
        $parent_id =Yii::$app->request->get('parent_id'); 
        $model = new Categories();
      	if(!empty($parent_id))
    	{ 
	    	return $model->find()->where(['parent_id'=>$parent_id,'app_id'=>1])->asArray()->all();	
    	}
    	else{
	    	return $model->find()->where(['OR',
                                               ['IS', 'parent_id', (new Expression('Null'))],
                                               ['parent_id' =>0]])->asArray()->all();	
    	}	    
     }

    public function actionSlider()
    {
        $parent_id =Yii::$app->request->get('parent_id'); 
        $model = new Sliders();
        if(!empty($parent_id))
        { 
            return $model->find()->where(['app_id'=>1])->asArray()->all();  
        }
        else{
            return $model->find()->where(['app_id'=>1])->asArray()->all();   
        }       
     }


}
