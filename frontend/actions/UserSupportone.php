<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use common\models\SupportSubject;
use common\models\Support;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
class UserSupportone extends Action
{

    public function run($id)
    {

     $model = new Support();
     
	 $subject = $this->findSubject($id);
	 $subject->flag_user = 0;
	 $subject->update();
	 
	 
	 $query = Support::find()->andWhere(['subject_id' => $id])
	->orderBy(['id' => SORT_ASC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '12']);
	$support = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
	 
    if ($model->load(Yii::$app->request->post())) {
		//Записываем тему
		$subject_add = $subject;
		$subject_add->date_update = date('Y-m-d H:i:s');
		$subject_add->flag_admin = 1;
		$subject_add->update();

		//Записываем сообщение
		$model->subject_id = $id;
		$model->date_add = date('Y-m-d H:i:s');
		$model->user_id = Yii::$app->user->id;
		$model->subject = 'true';
		$model->admin = 0;
		$model->save();
        $model = new Support();
		
		
	$query = Support::find()->andWhere(['subject_id' => $id])
	->orderBy(['id' => SORT_ASC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '12']);
	$support = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
    }
	 
	 return $this->controller->render('supportone', compact('model', 'support', 'subject', 'pages'));
	}
	
	
	
	
	
	
	
	
	
		

			
			
			
    protected function findSubject($id)
    {
		
       if (($model = SupportSubject::find()->where(['id' => $id,'user_id' => Yii::$app->user->id])->one()) !== null) {
            return $model;
       }
		

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}