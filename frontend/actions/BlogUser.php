<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use frontend\models\Blog;
use common\models\Rates;
use common\models\User;
use common\models\BlogTime;
use common\models\BlogImage;
use common\models\BlogField;
use common\models\CatServices;
use common\models\PaymentSystem;
use \yii\base\DynamicModel;
use yii\data\Pagination;
class BlogUser extends Action
{
    public function run($id)
    {


    $model = $this->findModel($id);
    $query = Blog::find()->andWhere(['user_id' => $id, 'status_id' => 1, 'active' => 1])->orderBy(['id' => SORT_DESC]);
	
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_page']]);
	$blog = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
    foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}
	$rates = Yii::$app->caches->rates();
	$notepad = Yii::$app->userFunctions->notepadArr();
	 $site_name = Yii::$app->caches->setting()['site_name'];
	 $meta['title'] = $model->username.' - Профиль пользователя на '.$site_name;
     $meta['h1'] = ' Профиль пользователя'. $model->username;
	 $meta['keywords'] = 'Пользователь,'.$model->username;
	 $meta['description'] = 'Информация о пользователе'. $model->username;
     $meta['breadcrumbs'][] = 'Информация о пользователе "'. $model->username.'"';
	 

	 

          return $this->controller->render('users', compact('model','user', 'rates', 'notepad', 'price', 'blog', 'pages', 'meta', 'fields'));

     }



    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}