<?php

namespace backend\modules\payment\controllers;

use Yii;
use backend\modules\payment\models\Payment;
use backend\modules\payment\models\PaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\payment\models\PaymentDetail;
use common\models\Model as Models;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\User;
use kartik\mpdf\Pdf;

//use backend\modules\credit\models\Contract;

/**
 * DefaultController implements the CRUD actions for Payment model.
 */
class DefaultController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex() {

        if (Yii::$app->user->can('finance')) {
            return $this->redirect(['/payment/consider']);
        }


        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = [
            'created_at' => SORT_DESC,
            'paid_at' => SORT_ASC,
        ];

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
        $modelDetails = $model->paymentDetails;
        return $this->render('view', [
                    'model' => $model,
                    'modelDetails' => $modelDetails,
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*
      public function actionCreate($id = NULL) {

      if ($id === NULL) {

      $model = new Payment();
      $seller = \common\models\User::getThisUser()->seller;
      $modelPays = Payment::getMustPays();
      $modelDetails = [];
      foreach ($modelPays as $key => $contract) {
      if ($contract->balances > 0) {
      $modelDetail = new PaymentDetail;
      $modelDetail->payment_id = $model->id;
      $modelDetail->contract_id = $contract->id;
      $modelDetail->amount = ($contract->credit->totalPay < $contract->balances) ? $contract->credit->totalPay : $contract->balances;
      //$detail->note = ;
      $modelDetails[] = $modelDetail;
      }
      }
      return $this->render('create', [
      'model' => $model,
      'seller' => $seller,
      'modelDetails' => $modelDetails
      ]);
      } else {
      $model = $this->findModel($id);
      $modelDetails = $model->paymentDetails;




      if ($model->load(Yii::$app->request->post())) {

      $post = Yii::$app->request->post();
      //                print_r($post);
      //                exit();
      $oldIDs = ArrayHelper::map($modelDetails, 'contract_id', 'contract_id');
      $modelDetails = Models::createMultiple(PaymentDetail::classname());
      Models::loadMultiple($modelDetails, Yii::$app->request->post());
      $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetails, 'contract_id', 'contract_id')));

      if (Yii::$app->request->isAjax) {
      Yii::$app->response->format = Response::FORMAT_JSON;
      return ArrayHelper::merge(
      ActiveForm::validateMultiple($modelDetails), ActiveForm::validate($model)
      );
      }
      $valid = TRUE;
      if (isset($post['send'])) {
      $model->scenario = 'confirm';
      $valid = $model->validate() && $valid;
      $valid = Models::validateMultiple($modelDetails) && $valid;
      }
      //echo $valid;
      //exit();
      if ($valid) {
      $transaction = \Yii::$app->db->beginTransaction();
      try {
      $model->seller_id = Yii::$app->user->id;
      $model->send_cash_amount = $model->send_by_cash ? $post['Payment']['send_cash_amount'] : null;
      $model->send_transfer_amount = $model->send_by_transfer ? $post['Payment']['send_transfer_amount'] : null;
      $model->send_cash_amount = str_replace(',', '', $model->send_cash_amount);
      $model->send_transfer_amount = str_replace(',', '', $model->send_transfer_amount);



      if ($flag = $model->save(false)) {


      PaymentDetail::deleteAll(['payment_id' => $model->id]);
      foreach ($modelDetails as $key => $contract) {
      $contract->payment_id = $model->id;
      $contract->contract_id = $post['PaymentDetail'][$key]['contract_id'];
      $contract->amount = str_replace(',', '', $post['PaymentDetail'][$key]['amount']);
      $contract->note = $post['PaymentDetail'][$key]['note'];
      $contract->reference = $post['PaymentDetail'][$key]['reference'];
      if (($flag = $contract->save(false)) === false) {
      $transaction->rollBack();
      break;
      }
      }
      } else {
      print_r($model->getErrors());
      exit();
      }
      if ($flag) {
      $transaction->commit();
      if (isset($post['save'])) {
      return $this->redirect(['create', 'id' => $model->id]);
      } else if (isset($post['send'])) {
      return $this->redirect(['confirm', 'id' => $model->id]);
      }
      }
      } catch (Exception $e) {
      $transaction->rollBack();
      }
      }
      }
      return $this->render('create', [
      'model' => $model,
      'modelDetails' => $modelDetails
      ]);
      }
      }
     */

    public function actionCreate($id = NULL) {

        if ($id === NULL) {

            $model = new Payment();
            $model->status = 0;
            $model->created_at = time();
            $model->seller_id = Yii::$app->user->id;
            $modelPays = Payment::getMustPays();
            if (!$modelPays) {
                Yii::$app->session->setFlash('warning', 'ไม่พบรายการที่ต้องส่งเงิน');
                return $this->redirect(['/payment/default/index']);
            }

            $transaction = \Yii::$app->db->beginTransaction();
            try {

                if ($flag = $model->save(false)) {
                    if ($modelPays) {
                        foreach ($modelPays as $key => $contract) {
                            if ($contract->balances > 0) {
                                $modelDetail = new PaymentDetail;
                                $modelDetail->payment_id = $model->id;
                                $modelDetail->contract_id = $contract->id;
                                $modelDetail->period = PaymentDetail::getPeriodPay($contract->id);
                                $modelDetail->amount = ($contract->credit->totalPay < $contract->balances) ? $contract->credit->totalPay : $contract->balances;
                                //$modelDetail->contract->balances
                                $modelDetail->old_balance = $contract->balances;
                                //$detail->note = ;
                                if (($flag = $modelDetail->save(false)) === false) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    } else {
                        $transaction->rollBack();
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    if ($model->save()) {
                        $this->redirect(['create', 'id' => $model->id]);
                    } else {
                        print_r($model->getErrors());
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            /**
             * บันทึก
             */
            $model = $this->findModel($id);
            if (!in_array($model->status, [0, 4])) {
                Yii::$app->session->setFlash('warning', 'ไม่พบสถานะที่จะยืนยันได้');
                return $this->redirect(['index']);
            }

            $modelDetails = $model->paymentDetails;
            $seller = User::getThisUser()->seller;

            if ($model->load(Yii::$app->request->post())) {

                $post = Yii::$app->request->post();
                //                print_r($post);
                //                exit();
                $oldIDs = ArrayHelper::map($modelDetails, 'contract_id', 'contract_id');
                $modelDetails = Models::createMultiple(PaymentDetail::classname());
                Models::loadMultiple($modelDetails, Yii::$app->request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetails, 'contract_id', 'contract_id')));

                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ArrayHelper::merge(
                                    ActiveForm::validateMultiple($modelDetails), ActiveForm::validate($model)
                    );
                }
                $valid = TRUE;
                if (isset($post['send'])) {
                    if (isset($post['Payment']['send_transfer_amount']))
                        $model->scenario = 'confirm';
                    $valid = $model->validate() && $valid;
                    $valid = Models::validateMultiple($modelDetails) && $valid;
                }
                //echo $valid;
                //exit();
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $model->seller_id = Yii::$app->user->id;
                        $model->send_cash_amount = $model->send_by_cash ? $post['Payment']['send_cash_amount'] : null;
                        $model->send_transfer_amount = $model->send_by_transfer ? $post['Payment']['send_transfer_amount'] : null;
                        $model->send_cash_amount = str_replace(',', '', $model->send_cash_amount);
                        $model->send_transfer_amount = str_replace(',', '', $model->send_transfer_amount);
                        $model->total_amount = $model->send_cash_amount + $model->send_transfer_amount;



                        if ($flag = $model->save(false)) {


                            PaymentDetail::deleteAll(['payment_id' => $model->id]);
                            foreach ($modelDetails as $key => $contract) {
                                $contract->payment_id = $model->id;
                                $contract->contract_id = $post['PaymentDetail'][$key]['contract_id'];
                                $contract->old_balance = $post['PaymentDetail'][$key]['old_balance'];
                                $contract->amount = str_replace(',', '', $post['PaymentDetail'][$key]['amount']);
                                $contract->balance = str_replace(',', '', $post['PaymentDetail'][$key]['balance']);

                                $contract->note = $post['PaymentDetail'][$key]['note'];
                                $contract->reference = $post['PaymentDetail'][$key]['reference'];
                                $contract->period = $post['PaymentDetail'][$key]['period'];
                                $contract->note = ($contract->balance == 0) ? 'ปิดยอด' : Null;
                                if (($flag = $contract->save(false)) === false) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        } else {
                            print_r($model->getErrors());
                            exit();
                        }
                        if ($flag) {
                            $transaction->commit();
                            if (isset($post['save'])) {
                                return $this->redirect(['create', 'id' => $model->id]);
                            } else if (isset($post['send'])) {
                                return $this->redirect(['confirm', 'id' => $model->id]);
                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }
            return $this->render('create', [
                        'model' => $model,
                        'modelDetails' => $modelDetails,
                        'seller' => $seller
            ]);
        }
    }

    public function actionConfirm($id) {
        $model = $this->findModel($id);

        if (!in_array($model->status, [0, 4])) {
            Yii::$app->session->setFlash('warning', 'ไม่พบสถานะที่จะยืนยันได้');
            return $this->redirect(['index']);
        }
        $modelDetails = $model->paymentDetails;

        if ($model->load(Yii::$app->request->post())) {
            $model->paid_at = time();
            $model->status = 1;
            if ($model->save()) {
                $modelProgress = new \backend\modules\payment\models\PaymentProgress();

                $modelProgress->status = null;
                $modelProgress->comment = 'ยื่นเสนอพิจารณา';
                $modelProgress->payment_id = $model->id;
                $modelProgress->created_by = Yii::$app->user->id;
                $modelProgress->created_at = time();

                if ($modelProgress->save(false)) {
                    Yii::$app->session->setFlash('success', 'ระบบได้ทำส่งใบนำส่งเงินแล้ว');
                    Yii::$app->notification->sentStaff('ขอยื่นใบส่งเงิน', Yii::$app->urlManager->createAbsoluteUrl(['/payment/default/view', 'id' => $model->id]), User::getThisUser()
                    );
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('confirm', [
                    'model' => $model,
                    'modelDetails' => $modelDetails,
        ]);
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

######################################################################
######################################################################

    public function actionUploadajax() {

        $this->uploadMultipleFile();
    }

    private function uploadMultipleFile() {
        $files = [];
        $json = '';
        if (Yii::$app->request->isPost) {
            $img = Yii::$app->img;
            $UploadedFiles = \yii\web\UploadedFile::getInstancesByName('Payment[attach_file]');
            $upload_folder = Yii::$app->request->post('upload_folder');
            $id = Yii::$app->request->post('id');
            $model = $this->findModel($id);
            $tempFile = Json::decode($model->attach_file);

            $pathFile = $img->getUploadPath() . $upload_folder;
//            print_r($tempFile);
//        exit();
            if ($UploadedFiles !== null) {
                $img->CreateDir($upload_folder);
                foreach ($UploadedFiles as $key => $file) {
                    try {
                        $oldFileName = $file->basename . '.' . $file->extension;
                        $newFileName = ltrim($file->basename, '0') . '.' . $file->extension;
//$newFileNameLarge = ltrim($file->basename, '0') . '-large.' . $file->extension;

                        $file->saveAs($pathFile . '/' . $newFileName);
                        $files[$newFileName] = $newFileName;
                        if (in_array($file->extension, ['png', 'jpg'])) {
                            $image = Yii::$app->image->load($pathFile . '/' . $newFileName);
                            $image->resize(150);
                            $image->save($pathFile . '/thumbnail/' . $newFileName);
                        }
                    } catch (Exception $e) {
                        
                    }
                }

//print_r($json);
                $model = $this->findModel($id);
                $model->attach_file = Payment::findFiles($pathFile);
                if ($model->save(false)) {
                    echo json_encode(['success' => 'true', 'file' => $files, 'temp' => $tempFile, 'json' => $json]);
                } else {
                    echo json_encode(['success' => 'false', 'error' => $model->getErrors()]);
                }
            } else {
                echo json_encode(['success' => 'false',]);
            }
        }
    }

    public function actionDeletefileAjax($id, $folder = null, $fileName = null) {
        $file = Yii::$app->img->getUploadPath($folder . '/' . $id) . $fileName;
        $pathFile = Yii::$app->img->getUploadPath($folder . '/' . $id);
        $model = Payment::findOne($id);
//        $data = Json::decode($model->image_id);
//        unset($data[$fileName]);

        if (@unlink($file)) {
            $model->attach_file = Payment::findFiles($pathFile);
            if ($model->save(false)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => $model->getErrors()]);
        }
    }

    public function actionReport($id, $download = NULL) {
// get your HTML raw content without any layouts or scripts
//        $model = $this->findModel($id);
//        $content = $this->renderPartial('view', [
//            'model' => $model,
//            'modelDetail' => $model->creditDetails
//        ]);

        $model = $this->findModel($id);
        $modelDetails = $model->paymentDetails;
        $content = $this->renderPartial('view', [
            'model' => $model,
            'modelDetails' => $modelDetails,
        ]);

// setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
// set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'filename' => Yii::$app->img->getUploadPath('credit') . 'credit_' . $model->id . '.pdf',
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
// enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => [
                'title' => 'รายงาน',
                'subject' => 'รายงาน1',
                'keywords' => 'รายงาน2',
            ],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => ['รายงานการขอยื่นจอง'],
                'SetFooter' => ['หน้าที่ {PAGENO}'],
            ]
        ]);

// return the pdf output as per the destination setting
        return $pdf->render();
    }

}
