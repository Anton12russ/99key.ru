<?php
namespace frontend\actions;
use yii\base\Action;
use common\models\SupportSubject;
use yii;
use common\models\Support;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
class UserSupportadd extends Action
{

    public function run()
    {

	$model = new Support();
    
    if ($model->load(Yii::$app->request->post())) {
		//Записываем тему
		$subject = new SupportSubject();
		$subject->user_id = Yii::$app->user->id;
		$subject->subject = $model->subject;
		$subject->date_add = date('Y-m-d H:i:s');
		$subject->date_update = date('Y-m-d H:i:s');
		$subject->status = 1;
		$subject->flag_user = 0;
		$subject->flag_admin = 1;
		$subject->save();

		//Записываем сообщение
		$model->subject_id = $subject->id;
		$model->date_add = date('Y-m-d H:i:s');
		$model->user_id = Yii::$app->user->id;
		$model->admin = 0;
		if($model->save()) {
			
			//-------------------------Отправка оповещение админу-----------------------------//
			  if(Yii::$app->caches->setting()['email_support']) {
			      Yii::$app->functionMail->support_admin(Yii::$app->user->identity->email, Yii::$app->caches->setting()['email_support']);
			  }
			 return $this->controller->redirect(['user/supportone', 'id' => $subject->id, 'add' => true]);
		}
		
		
    }


	 
	return $this->controller->render('supportadd', compact('model'));
	}
	

}