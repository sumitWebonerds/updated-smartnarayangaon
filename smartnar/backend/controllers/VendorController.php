<?php

namespace backend\controllers;

use Yii;
use common\models\Vendor;
use common\models\VendorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\Categories;
use common\models\VendorCategories;
/**
 * VendorController implements the CRUD actions for Vendor model.
 */
class VendorController extends Controller
{
    /**
     * @inheritdoc
     */
   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','view','update','delete','logout'],
                'rules' => [
                    [
                        'actions' => ['index','create','view','update','delete','logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Vendor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubcategory()
    {
            $ids =Yii::$app->request->get('id'); 
            $model = new Categories();
            $categories = \common\models\Categories::find()
                    ->where(['in','parent_id', $ids])
                    ->all(); 
            if(count($categories) > 0){
                foreach($categories as $post){
                    echo "<option value='".$post->id."'>".$post->category_name."</option>";
                }
            }
            else{
                echo "<option>-</option>";
            }
    }
    
    /**
     * Displays a single Vendor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Vendor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vendor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $subcategories = Yii::$app->request->post('Vendor')['subcategory_id'];             
            $app_id = Yii::$app->request->postmodel('Vendor')['app_id'];
            
            foreach($subcategories as $category)
            {   

                $vendorCategory = new VendorCategories();
                $vendorCategory->app_id =$app_id;
                $vendorCategory->vendor_id = $model->id;
                $vendorCategory->category_id = $category;                 
                $vendorCategory->save(); 
            }

            $imageName = "vendor_image_".rand();
            
            $model->file = UploadedFile::getInstance($model,'shop_image');
            if(!empty($model->file)){      
            $model->file->saveAs('../images/vendors/'.$imageName.'.'.$model->file->extension);
            
            $model->shop_image = 'vendors/'.$imageName.'.'.$model->file->extension;
            }
            if($model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                            return $this->render('create', [
                'model' => $model,
            ]);                
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Vendor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {


        $model = $this->findModel($id);
        $vendorCategory = new VendorCategories();
        if ($model->load(Yii::$app->request->post())) {
            $model->lognitude =  Yii::$app->request->post('Vendor')['lognitude']; 
            $model->latitude =  Yii::$app->request->post('Vendor')['latitude']; 
            
            $subcat=  $vendorCategory->load(Yii::$app->request->post('Vendor')['subcategory_id']); 
            $id = $vendorCategory->load(Yii::$app->request->post('Vendor')['app_id']);
            
            $subcategories =  Yii::$app->request->post('Vendor')['subcategory_id']; 
            $app_id = Yii::$app->request->post('Vendor')['app_id'];
            if(!empty($subcategories)){
                foreach($subcategories as $category)
                {   
                    $vendorCategory->app_id =$id;
                    $vendorCategory->vendor_id = $model->id;
                    $vendorCategory->category_id = $category;                 
                    $vendorCategory->save(false); 
                } 
            }
            //print_r($vendorCategory);exit;
            $imageName = "vendor_image_".rand();

            $model->file = UploadedFile::getInstance($model,'shop_image');
            
            
            if(!empty($model->file)){

                $model->file->saveAs('../images/vendors/'.$imageName.'.'.$model->file->extension);

                $model->shop_image = 'vendors/'.$imageName.'.'.$model->file->extension;

                $model->save(false);

                return $this->redirect(['view', 'id' => $model->id]);
           
            }else{

                $model->save(false);
                return $this->redirect(['view', 'id' => $model->id]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Vendor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Vendor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vendor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
