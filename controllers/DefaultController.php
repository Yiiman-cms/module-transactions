<?php

namespace system\modules\transactions\controllers;

use system\modules\transactions\base\PaymentTerminal;
use Yii;
use system\modules\language\models\Language;
use system\modules\transactions\models\Transactions;
use system\modules\transactions\models\SearchTransactions;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Transactions model.
 */
class DefaultController extends \system\lib\Controller{
	/**
	*
	* @var $model SearchTransactions	*/
	public $model;
	public $enableCsrfValidation=false;
    /**
     * {@inheritdoc}
     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }

    /**
     * Lists all Transactions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchTransactions();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transactions model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model=$this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Transactions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transactions;

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Transactions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model=$this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if( $model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Transactions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionLoadform()
    {
        $post = Yii::$app->request->post();

        if (!empty($post['id'])) {
            $gateClass=\stdClass::class;
            eval('$gateClass = new system\modules\transactions\Terminals\\' . $post['id'].';');
            /**
             * @var $gateClass PaymentTerminal
             */
            return $gateClass->renderForm();
        }
    }

    public function actionLoadJs()
    {
        $post = Yii::$app->request->post();

        if (!empty($post['id'])) {
            $gateClass=\stdClass::class;
            eval('$gateClass = new system\modules\transactions\Terminals\\' . $post['id'].';');
            /**
             * @var $gateClass PaymentTerminal
             */
            $out= '<script>'.$gateClass->renderJS().'</script>';
            return $out;
        }
    }



	protected function upload(){
	
	
	}

	public function init(){
        parent::init();
		$this->modelClass=new Transactions();
	}
}
