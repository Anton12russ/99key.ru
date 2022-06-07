<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use common\models\Blog;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\base\DynamicModel;
use yii\web\Response;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
	
	
	
	
  public function actionSay($message = 'Привет')
    {
    return $this->render('say', ['message' => $message]);
    }	
	
	
	
	
	
	
	
	
	
	
		
	public function actionSaveRedactorImg($sub)
    {
        $this->enableCsrfValidation = false;

        if (Yii::$app->request->isPost) {
            $dir = Yii::getAlias('@images_all').'/'.$sub.'/';
        if (!file_exists($dir)) {
                FileHelper::createDirectory($dir);
            }
        // $result_link = Url::home(true) . 'uploads/images/' . $sub . '/';

   $result_link = str_replace('admin/','',Url::home(true)).'images_all/'.$sub.'/';
            $file = UploadedFile::getInstanceByName('file');
            $model = new DynamicModel(compact('file'));
            $model->addRule('file', 'image')->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {
				//Имя файла
                $model->file->name = strtotime('now').'_'.Yii::$app->getSecurity()->generateRandomString(6) . '.' . 
				$model->file->extension;
                if ($model->file->saveAs($dir . $model->file->name)) {
					
                $imag = Yii::$app->image->load($dir . $model->file->name);
                $imag -> resize (800, NULL, Yii\image\drivers\Image::PRECISE)
                ->save($dir . $model->file->name, 85); 
                    $result = ['filelink' => $result_link . $model->file->name,'filename' => $model->file->name];
                } else {
                    $result = [
                        'error' => Yii::t('vova07/imperavi', 'ERROR_CAN_NOT_UPLOAD_FILE')
                    ];
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
           
		   return $result;
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }
	
	
	public function actionImgDel($file, $act)
    {
		
        if($act == 'article') {
	         $dir = Yii::getAlias('@images_all').'/'.$act.'/'.pathinfo($file , PATHINFO_BASENAME );
        }

        @unlink($dir);
		return 'Изображение удалено с сервера';	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }
	
  public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}
