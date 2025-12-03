<?php

namespace app\modules\ecosmob\dbbackup\controllers;

use app\modules\ecosmob\dbbackup\models\DbBackup;
use app\modules\ecosmob\dbbackup\models\DbBackupSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\ecosmob\dbbackup\DbBackupModule;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * Class DbBackupController
 *
 * @package app\modules\ecosmob\dbbackup\controllers
 */
class DbBackupController extends \yii\web\Controller
{

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
			    'download',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new DbBackupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'sort' => ['defaultOrder' => ['start_epoch' => SORT_DESC]],
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionDownload($id)
    {
	$model = DbBackup::findOne($id);
	if(!file_exists($model->db_path))
	{
		Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'something_wrong'));
		return $this->redirect(['index']);
	}
	$file_url = $model->db_path;
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . basename($model->db_name) . "\""); 
	readfile($file_url); 
	exit;

	/* $file_url = $model->db_path;
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . basename($model->db_name) . "\""); 
	readfile($file_url); 
	exit;
	*/

	/* $file_url = $model->db_path;

	$zip_file_name = $model->db_name.".zip";
	
	$zip_file_path = "/tmp/".$zip_file_name;
	$command = "zip -rej --password b4445ad06bed98d823a47be41a943d7e $zip_file_path ".$model->db_path;	    
	$output = shell_exec($command);
	if(!file($zip_file_path))
	{
		Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'something_wrong'));
		return $this->redirect(['index']);
	}
        header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . basename($zip_file_name) . "\""); 
	readfile($zip_file_path); 
	exit; */
    }

    public function actionCreate()
    {
        $model=new DbBackup();

        if ($model->load(Yii::$app->request->post())) 
	{
		/* if(!(Yii::$app->request->post()))
		{
			Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'file_not_allowed'));
			return $this->redirect(['index']);
		}
		$fileName = UploadedFile::getInstance($model, 'db_name');	
		//print_r($fileName);exit;
		//print_r(Yii::$app->request->post());exit;
		$model->db_name = $fileName;
		if (!$model->db_name->hasError) 
		{
			$audioFilePath = Url::to(Yii::$app->params['WEB_SQL_PATH']);
			$extension = pathinfo($model->db_name, PATHINFO_EXTENSION);
			if($extension != "sql")
			{
				Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'file_not_allowed'));
				return $this->redirect(['index']);
			}
			$af_name = urlencode($model->db_name);
			$file_sql_name = "calltech_up_".date('Y-m-d H:i:s').".sql";
			$uploadFileName = $audioFilePath . $file_sql_name;

			if ($model->db_name->saveAs($uploadFileName, FALSE)) 
			{
				$model->db_name = $file_sql_name;
				$model->db_date = date('Y-m-d');
				$model->db_created_date = date('Y-m-d H:i:s');
				$model->db_path = $uploadFileName;
				if ($model->save()) 
				{
				    Yii::$app->session->setFlash('success', DbBackupModule::t('app', 'created_success'));
				    return $this->redirect(['index']);
				}
			}
		} */

		/* if(!(Yii::$app->request->post()))
		{
			Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'file_not_allowed'));
			return $this->redirect(['index']);
		}
		$fileName = UploadedFile::getInstance($model, 'db_name');	
		$model->db_name = $fileName;
		if (!$model->db_name->hasError) 
		{
			$audioFilePath = Url::to(Yii::$app->params['WEB_SQL_PATH']);
			$extension = pathinfo($model->db_name, PATHINFO_EXTENSION);
			if($extension != "zip")
			{
				Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'file_not_allowed'));
				return $this->redirect(['index']);
			}
			//$af_name = urlencode($model->db_name);
			//$file_sql_name = "calltech_up_".date('Y-m-d_H:i:s').".zip";

			$file_sql_name = $fileName;
			$uploadFileName = $audioFilePath . $fileName;
			if ($model->db_name->saveAs($uploadFileName, FALSE)) 
			{
				$model->db_name = $file_sql_name->name;
				$model->db_date = date('Y-m-d');
				$model->db_created_date = date('Y-m-d H:i:s');
				$model->db_path = $uploadFileName;
				$model->save();
				if ($model->save()) 
				{
				    Yii::$app->session->setFlash('success', DbBackupModule::t('app', 'created_success'));
				    return $this->redirect(['index']);
				}
			}
		} */

		if(!(Yii::$app->request->post()))
		{
			Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'file_not_allowed'));
			return $this->redirect(['index']);
		}
		$fileName = UploadedFile::getInstance($model, 'db_name');	
		$model->db_name = $fileName;
		if (!$model->db_name->hasError) 
		{
			$audioFilePath = Url::to(Yii::$app->params['WEB_SQL_PATH'].$GLOBALS['tenantID']."/");
			$extension = pathinfo($model->db_name, PATHINFO_EXTENSION);
			if($extension != "zip")
			{
				Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'file_not_allowed'));
				return $this->redirect(['index']);
			}

			if (!strpos($model->db_name, '.sql.zip'))
			{
				Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'file_not_allowed'));
				return $this->redirect(['index']);
			}
			//$file_sql_name ="calltech_up_".date('Y-m-d_H:i:s').".sql.zip";;
			//$uploadFileName = $audioFilePath . $file_sql_name;


			try {
			        $fileName = $model->db_name;
				$uploadFileName = $audioFilePath . $fileName;
				if ($model->db_name->saveAs($uploadFileName, FALSE)) 
				{
					$model->db_name = $fileName->name;
					$model->db_date = date('Y-m-d');
					$model->db_created_date = date('Y-m-d H:i:s');
					$model->db_path = $uploadFileName;
					$model->save();
					if ($model->save()) 
					{
					    Yii::$app->session->setFlash('success', DbBackupModule::t('app', 'created_success'));
					    return $this->redirect(['index']);
					}
				}
			} catch (ErrorException $e) {
				Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'backup_something_wrong_msg'));		
				return $this->redirect(['index']);
			}
				
		}
            
        }

        return $this->render(
            'create',
            [
                'model'=>$model,
            ]
        );
    }	

    public function actionUpdate($id)
    {
	$count = DbBackup::find()->where(["db_restore" => "1"])->asArray()->all();
	if(!empty($count))
	{
		Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'backup_proccess_another'));
		return $this->redirect(['index']);
	}

	$count = DbBackup::find()->where(["db_restore" => "2"])->asArray()->all();
	if(!empty($count))
	{
		Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'backup_proccess_another'));
		return $this->redirect(['index']);
	}

	Yii::$app->db->createCommand("UPDATE calltech_backup .db_backup set db_restore = '0' WHERE db_restore = '3' or db_restore = '4'")->execute();

	$model = DbBackup::findOne($id);	
	if($model !== null) 
	{
		if($model->db_restore == "2")
		{
			Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'backup_proccess_already_started'));
			return $this->redirect(['index']);
		}

		$model->db_restore = '1';
		$model->db_restore_date = date('Y-m-d H:i:s', strtotime('+ 10 minute', strtotime(date('Y-m-d H:i:s'))));
		$model->save();
		Yii::$app->session->setFlash('success', DbBackupModule::t('app', 'backup_proccess_start_soon_msg'));
		return $this->redirect(['index']);
	}
	else
	{
		Yii::$app->session->setFlash('error', DbBackupModule::t('app', 'backup_something_wrong_msg'));		
		return $this->redirect(['index']);
	}

	/* if (($model = DbBackup::findOne($id)) !== null) 
	{
		$model->db_restore = '1';
		$model->db_restore_date = date('Y-m-d H:i:s', strtotime('+ 10 minute', strtotime(date('Y-m-d H:i:s'))));
		$model->save();
		//Yii::$app->session->setFlash('success', DbBackupModule::t('app', 'backup_proccess_start_in_msg'));
		Yii::$app->session->setFlash('success', DbBackupModule::t('app', 'backup_proccess_start_soon_msg'));
	        return $this->redirect(['index']);
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));        */
    }
}

