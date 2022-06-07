<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use frontend\models\User;
use frontend\models\Article;
use common\models\ArticleCat;
use common\models\ArticleComment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\helpers\Url;
/**
 * BlogController
 */
class ArticleController extends Controller
{

	//Страница объявления
	public function actionOne($id, $url)
    {
		
	
  // print_r($id_one);
   $art = Article::find()->andWhere(['id'=>$id])->one();
   if (!isset($art->status)) {
         throw new NotFoundHttpException('Такой статьи нет или она находится на модерации');
   }
   if(!Yii::$app->user->can('updateArticle')) {
       if (isset($art->status) && $art->status != 1 ) {
	         if ($art->user_id != Yii::$app->user->id) {
	             throw new NotFoundHttpException('Такой статьи нет или она находится на модерации');
		     }
       }
   }

	    $url_original = 'article/'.Yii::$app->caches->artcat()[$art['cats']['id']]['url'] . '/' . Yii::$app->userFunctions->transliteration($art['title']).'_'.$art['id'].'.html';


	  //Проверяем, совпадает ли url с оригиналом
	   if($url_original == $url) {
		   
		//Получаем мета теги
	   $meta = Yii::$app->userFunctions->metaOneart($art['cats']['id'], $art->title, 'article');


	 //Добавление комментария
        $comment_add = new ArticleComment();
		$comment_save = '';
		
		
	//Сохраняем	Комментарий ----------------------------------------------------------------------------------
     if ($comment_add->load(Yii::$app->request->post())) {
		  $comment_add->blog_id = $art->id;
		  $comment_add->date = date('Y-m-d H:i:s');
		  $comment_add->status = Yii::$app->caches->setting()['moder_blog_comment'];
	      if ($comment_add->too) {
			  $comment_add->text = preg_replace('/^.+\n/', '', $comment_add->text);
		  }
		  
		  //Добавляем данные юзера, если он зарегистрирован
		  if (!Yii::$app->user->isGuest) {
			 $comment_add->user_id = Yii::$app->user->id;
			 $comment_add->user_email = Yii::$app->user->identity['email'];
		     $comment_add->user_name = Yii::$app->user->identity['username'];
		  }
          
          $comment_add->save();
		  $comment_save = true;

		  		$user_c = User::findOne($art->user_id);

			     if(!isset($user_c->alert['comment']) || (isset($user_c->alert['comment']) && $user_c->alert['comment'] == 1)) {
					$url = Url::to(['article/one', 'category'=>$art->cats['url'], 'id'=>$art->id, 'name'=>Yii::$app->userFunctions->transliteration($art->title)]);
			         Yii::$app->functionMail->comment_article($user_c, $url);
			    }
		  
    }
	//Сохранили	Комментарий ----------------------------------------------------------------------------------		 
	$query = ArticleComment::find()->andWhere(['blog_id' => $art->id, 'status' => 1])->orderBy(['id' => SORT_DESC]);
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
	$comment = $query->offset($pages->offset)
     ->limit($pages->limit)
     ->all();
		  

         $like = Yii::$app->userFunctions->likeArr();
         if(isset($like[$art->id])){
			 $like = true;
		 }else{
			 $like = '';
		 }
          return $this->render('one', compact('art','pages', 'meta', 'comment', 'comment_add', 'comment_save', 'count_com', 'vote', 'like'));
	   }else{
		  throw new NotFoundHttpException('Страница не найдена.');
	   }

}



	
    public function actionIndex($category, $patch)
    {
		
	$text = Yii::$app->request->get('text');
	if ($text) {
	$text = Html::encode($text);
	}
	
	
	
    if ($category) {
	    $cat_all = Yii::$app->userFunctions->recursСatart($category);
		}else{
			$category = 0;
			$cat_all = '';
		}

	//Получаем список категорий на странице выбранной категории
	$cat_menu = Yii::$app->userFunctions->menuCatarticle($category, $patch);
	if ($category) {
            $params = ['status'=>1, 'cat'=>$cat_all];
	}else{
		    $params = ['status'=>1];
	}
	
			//Пареметры для сортировки 
	 if (Yii::$app->request->get('sort')) {  
	 if (Yii::$app->request->get('sort') == 'DESC') $sort = SORT_DESC; 
	 if (Yii::$app->request->get('sort') == 'ASC') $sort = SORT_ASC; 
	 }else{
		 $sort = '';
	 }
	$sql = Article::find()->with('cats'); 
	$sql->andFilterWhere(['like', 'title', $text]);
	if ($sort) {
	   $query = $sql->andWhere($params);
	     //Для модератора
		   if(Yii::$app->user->can('updateArticle')) {$sql->orWhere(['status'=>0]);}
	   $sql->orderBy(['rayting' => $sort]);
	}else{
       $query = $sql->andWhere($params);
	     //Для модератора
	     if(Yii::$app->user->can('updateArticle')) {$sql->orWhere(['status'=>0]);}
	   $sql->orderBy(['date_add' => SORT_DESC,]);
	}
    
    $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => Yii::$app->caches->setting()['max_count_main']]);
	$article = $query->offset($pages->offset)
    ->limit($pages->limit)
    ->all();
	$metaCat = Yii::$app->userFunctions->metaArtmeta($category);
    return $this->render('all', compact('article', 'pages', 'metaCat' , 'cat_menu'));
    }



    //Страница объявления
	public function actionLike($id)
    {
	$arr = unserialize(Yii::$app->request->cookies['like']);
	 if ($arr) {
	    $search = array_search($id, $arr);

        if ($search){
             unset($arr[$search]);
			  $re = 0;
        }else{
	    	 array_push($arr, $id);
	          $re = 1;
	    }
	 }else{
		 $arr[1] = $id;
		 $re = 1;
	 }
		
	    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'like',
                        'value' =>  serialize($arr)
                        ]));
		
		if ($re) {
              $model = $this->findLike($id);
	      if($re == 1) {  	
              $model->rayting = $model->rayting + 1;
			  $model->update(false);	
			  return 1;
	      }elseif($re == 0){
              $model->rayting = $model->rayting - 1;
			  if($model->rayting >= 0) {
			     $model->update(false);	
			  }
			  return 0;	
		  }	  
		}else{
			  $model = $this->findLike($id);
              $model->rayting = $model->rayting - 1;
			    if($model->rayting >= 0) {
			     $model->update(false);	
			  }
			  return 0;
		  }		
         	  
	}
	
	
	 public function actionExit()
 {

$get = Yii::$app->request->get();	
$arr = ArticleCat::find()->orderBy('sort')->asArray()->all();
function linenav($cat, $cats_id, $first = true)
    {
  static $array = array();
    $value = ArticleCat::find()->where(['id' => $cats_id])->orderBy('sort')->asArray()->one();

    if($value['parent'] != 0 && $value['parent'] != "")
       {
           linenav($cat, $value['parent'], false);
       }
   $array[] = array('name' => $value['name'], 'id' => $value['id'], 'parent' => $value['parent']);
    foreach($array as $k=>$v)
        {
		$next = $v['id'];
		if(!isset($return)) {$return = '';}
		$return .= '<select class="form-control sel_cat">';
        $return .= '<option value="false">Не выбрано</option>';	
		foreach($cat as $row) {
		
		if ($row['parent']==$v['parent']) {
			$select = '';
		   if ($row['id']==$v['id']) {$select = 'selected="selected"';}
			$return .= '<option '.$select.' value="'.$row['id'].'">'.$row['name'].'</option>';
			
		 }
		}
		 $return .= '</select>';
		 
	
        }

    return $return;
    }
	
if(isset($get['id_cat'])) {
unset($arr[$get['id_cat']]);
}
 return linenav($arr, $get['idcategory']);
	}
	
	
	
	public function actionImgDel($file, $act)
    {
		
        if($act == 'article') {
	         $dir = Yii::getAlias('@images_all').'/'.$act.'/'.pathinfo($file , PATHINFO_BASENAME );
        }

        @unlink($dir);
		return 'Изображение удалено с сервера';	
	}

    protected function findLike($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }




	public function actions()
    {
    return [
            'add' => [
                'class' => 'frontend\actions\ArticleAdd',
            ],
			
			'update' => [
                'class' => 'frontend\actions\ArticleUpdate',
            ],
			
			'del' => [
                'class' => 'frontend\actions\ArticleDel',
            ],
			'active' => [
                'class' => 'frontend\actions\ArticleActive',
            ],
    ];
}
	
	
}