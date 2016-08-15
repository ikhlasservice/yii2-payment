<?php

namespace backend\modules\payment\controllers;

use Yii;
use backend\modules\payment\models\Payment;
use backend\modules\payment\models\PaymentConsiderSearch;
use backend\modules\payment\models\PaymentDetail;
use backend\modules\contract\models\Contract;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\payment\models\PaymentProgress;

/**
 * ConsiderController implements the CRUD actions for Payment model.
 */
class ConsiderController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PaymentConsiderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $modelProgress = new PaymentProgress();
            $valid = $modelProgress->load(Yii::$app->request->post());
            $modelProgress->payment_id = $model->id;
            $modelProgress->created_by = Yii::$app->user->id;
            $modelProgress->created_at = time();
            switch ($modelProgress->status) {

                case 1:
                    $model->status = 3;
                    break;

                case 2:
                    $model->status = 4;
                    break;
            }
            $model->staff_id = Yii::$app->user->id;
            if ($model->save() && $valid) {
                if ($modelProgress->save(false)) {
                    $this->checkOffBalance($model->id); //เช็คปิดสัญญา
                    Yii::$app->session->setFlash('success', 'บันทึกและส่งเมลล์แล้ว');
                    Yii::$app->notification->sent('เรียบร้อย', \yii\helpers\Url::to(['/payment/result/view', 'id' => $model->id]), $model->seller, $model->staff);
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Payment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function checkOffBalance($pament_id) {
        $models = PaymentDetail::find()
                ->select(['payment_detail.contract_id'])
                ->joinWith('payment')
                ->where([
                    //'payment.status'=>3,
                    'payment.id' => $pament_id,
                    'payment_detail.balance' => [0, 0.00]
                ])
                ->all();
        if ($models) {
            foreach ($models as $model) {
                $model = Contract::findOne($model->contract_id);
                $model->status = 0;
                $model->save();
            }
            
        }
        //print_r($model);
    }

}
