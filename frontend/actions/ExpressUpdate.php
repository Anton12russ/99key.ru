<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use yii\helpers\Url;
use frontend\models\BlogExpress;
use common\models\Rates;
use common\models\User;
use common\models\Field;
use common\models\BlogTime;
use common\models\BlogImage;
use common\models\BlogField;
use common\models\BlogCoord;
use common\models\BlogAuction;
use common\models\AuctionCat;
use common\models\BlogKey;
use common\models\CatServices;
use common\models\PaymentSystem;
use yii\web\NotFoundHttpException;
use \yii\base\DynamicModel;
class ExpressUpdate extends Action
{
    public function run($id)
    {

		$model = $this->findModel($id);
		//---Обновление координаты ---//
		if(isset($model->coord->text)) {
		  $model->address = $model->coord->text;
		  $model->coordlat = $model->coord->coordlat;
		  $model->coordlon = $model->coord->coordlon;
		}
	
		   //Копируем изображения во временную папку
		   $dir_name = $id;
		   if (!Yii::$app->request->post()) {
		   $base = BlogImage::find()->where(['blog_id'=>$id])->select(['image'])->asArray()->all();
		   $this->findFile($id, $base);
		   }

	 //Для проверки изменения категории, а вдруг выбрали платную.
	 $category_pay = $model->category;
	 $region_pay = $model->region;
	 

	 $catid = $model->category;
	  if (Yii::$app->request->post('Pjax_category')) {
		   $catid = Yii::$app->request->post('Pjax_category');
 //------------------------Проверяем, платная ли категория------------------//
		$reg_price = Yii::$app->request->post('Pjax_region');	
        $time_price	= Yii::$app->request->post('Pjax_time');
	    $price_category = $this->findMod($catid, $reg_price);	
		


        if (isset($price_category)) {
			$price_cat = array();
			$price_cat['price'] = (int)$price_category * (int)$time_price;
			$price_cat['tyme'] = $time_price;
			$price_cat['sum'] = $price_category ;
		}
		  
        }
		if(@Yii::$app->request->post()['Blog']['category']) {
               $catid =  Yii::$app->request->post()['Blog']['category'];	
		}
		
		

        $array_post = Yii::$app->request->post();
		$model2_arr =  $this->findModel2($id);     

          if(isset($model2_arr['f_481'])) {
		     $model->price = $model2_arr['f_481']['value'];
			 if($model->price == '0') {
				$model->price = '';
			 }
		  }
		  if(isset($model2_arr['f_475'])) {
			$model->phone = $model2_arr['f_475']['value'];
		   }
	
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

		$model->url = Yii::$app->userFunctions->transliteration($model->title);
		if ($model->dir_name) {$dir_name = $model->id;}

		//Модерация объявления
		if(Yii::$app->caches->setting()['moder']) {
			$model->status_id = 1;	
		}else{
			$model->status_id = 0;
		}
		
		$model->status_id = 0;	
		//------------------------Проверяем, платная ли категория------------------//
	    $price_category = $this->findMod($model->category, $model->region);
		if ($price_category) {
			$price_datedel = $model->date_del;
			 $model->active = 0;
		}	else{
             $model->active = 1; 
		}	

		
		//передаем эту переменную в шаблон, чтобы вывести сообщение с успешным размещением
		$save['status'] = $model->status_id;

        $model->date_update =  date('Y-m-d H:i:s');
	
		$model->date_del =  date('Y-m-d H:i:s', strtotime(' + '.Yii::$app->caches->setting()['express_add'].' day'));

		//Сохраняем статичные поля, проверка внутри скрыта, чтобы не проверять повторно пользователя
       

	
		if (Yii::$app->user->can('updateBoard')) {
			$model->status_id = $model->status_id_false;
			$model->active = $model->active_false;
		}

		$model->update(false);
		BlogField::deleteAll('message = :message', [':message' => $model->id]);	
//цена
if(!$model->price) {
	$model->price = '0';
}
	  $filprice = new BlogField();
	  $filprice->message = $model->id;
	  $filprice->field  = '481';
	  $filprice->value  = $model->price;
	  $filprice->dop  = '1';
	  $filprice->save();

//Телефон
$filphone = new BlogField();
$filphone->message = $model->id;
$filphone->field  = '475';
$filphone->value  = $model->phone;
$filphone->dop  = '';
$filphone->save();	


				//------------------Обновление ---------------Добавляем координаты-------------------//
		
		           $coord = new BlogCoord();
                   $coord->blog_id = $model->id;
                   $coord->coordlat  = $model->coordlat;
                   $coord->coordlon  = $model->coordlon;
				   $coord->text  = $model->address;
                   $coord->save();
		
		$save['id'] = $model->id;
		//передаем количество дней
		$save['date_del'] = Yii::$app->caches->setting()['express_add'];
		
		
		if ($price_category) {
			$price_cat = array();
			$sum_pay = (int)$price_category * (int)$price_datedel;
			$price_cat['sum'] = $sum_pay;
			$price_cat['date'] = $price_datedel;
			$price_cat['id_payment'] = Yii::$app->userFunctions->addPayment($sum_pay, $model->id, $user_id);
		}
		
		




	  //Обрабатываем массив с данными и группируем валюту и адрес в поле dop
	  $post_save = array();


		            $dir_maxi = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
		            $dir_mini = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/mini/';
		            $dir_original = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/original/';
				    $dir_copy_maxi = Yii::getAlias('@img').'/board/maxi/';
		            $dir_copy_mini = Yii::getAlias('@img').'/board/mini/';
		            $dir_copy_original = Yii::getAlias('@img').'/board/original/';
					
					
			//Функция перемещения изображений из временной папки в папку с фото
			$list = Yii::$app->userFunctions->filesDir($dir_name);
			if ($list) 
			{
				
				
			$base = BlogImage::find()->where(['blog_id'=>$model->id])->select(['image'])->asArray()->all();
			foreach($base as $res) {
				 
						  @unlink($dir_copy_maxi.$res['image']);
						  @unlink($dir_copy_mini.$res['image']);
						  @unlink($dir_copy_original.$res['image']);
					
			}
				BlogImage::deleteAll('blog_id = :blog_id', [':blog_id' => $model->id]);
			   foreach($list as $key => $file) {
				@rename($dir_maxi.$file, $dir_copy_maxi.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.jpg');
				@rename($dir_mini.$file, $dir_copy_mini.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.jpg');
				@rename($dir_original.$file, $dir_copy_original.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.jpg');
				
				//Сохраняем название файла в базу данных
					  $blogimage = new BlogImage();
					   $blogimage->id = '';
					   $blogimage->blog_id = $model->id;
					   $blogimage->image  = $model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.jpg';
					   $blogimage->save();
			   }			
			}	
			Yii::$app->userFunctions->recursiveRemove(Yii::getAlias('@images_temp').'/board/'.$dir_name.'/');

         
		
		  //-------------------------Отправка оповещение админу-----------------------------//
			  if(Yii::$app->caches->setting()['email_board']) {
				  $url = Url::to(['blog/one', 'region'=>$model->regions['url'], 'category'=>$model->categorys['url'], 'url'=>$model->url, 'id'=>$model->id]);
				  Yii::$app->functionMail->express_admin(Yii::$app->caches->setting()['email_board']);
			  }  
	   }else{
	
		   //Для разработчика
		   	$print = $model->errors;
	   }
	
      

		
		if (!isset($print)) {$print = '';}
        if ($model->dir_name) {$dir_name = $model->dir_name;}
		if(!isset($price_cat))	 {$price_cat = '';}
		if(!isset($save)) {$save = '';}

	 //Мета теги
	 //Если выбран регион, добавляем к нему склонение
     	if (Yii::$app->request->cookies['region']) {
		   $regionid = (string)Yii::$app->request->cookies['region'];
	       $region_sklo = ' в ' .Yii::$app->caches->regionCase()[$regionid]['name'];
		   $region_key =   ', '.Yii::$app->caches->region()[$regionid]['name'];
		}else{
			$region_sklo = '';
			$region_key = '';
		}
		
	 $meta['title'] = 'Подать объявление '.$region_sklo;
     $meta['h1'] = $meta['title'];
	 $meta['keywords'] = 'подать, объявление'.$region_key;
	 $meta['description'] = 'Добавить объявление'. $region_sklo;
     $meta['breadcrumbs'] = 'Добавить объявление';



	 	//Получаем все платежные системы и передаем в шаблон
	    $payment = $this->findPayment();
	


		//Для отладки страницы save
		/*$save= array();
		$save['id'] = '3708';
		$save['date_del'] = Yii::$app->caches->setting()['express_add'];
		$save['status'] = 1; */
		// ----------------------------------------------------------//
		 //Если выбран регион, добавляем к нему склонение
     	if (Yii::$app->request->cookies['region']) {
			$regionid = (string)Yii::$app->request->cookies['region'];
			$region_sklo = ' в ' .Yii::$app->caches->regionCase()[$regionid]['name'];
			$region_key =   ', '.Yii::$app->caches->region()[$regionid]['name'];
		 }else{
			 $region_sklo = '';
			 $region_key = '';
		 }
		 
	  $meta['title'] = 'Редактировать экспресс объявление'.$region_sklo;
	  $meta['h1'] = $meta['title'];
	  $meta['keywords'] = 'редактировать, экспресс, объявление'.$region_key;
	  $meta['description'] = 'Редактировать экспресс объявление'. $region_sklo;
	  $meta['breadcrumbs'] = 'Редактировать экспресс объявление';
         return $this->controller->render('update_express', [
            'model' => $model,
			'print' => $print,
			'model_view' => $model_view,
			'dir_name' => $dir_name,
			'save' => $save,
			'meta' => $meta,
			'price_category' => $price_cat,
			'payment' => $payment,
			'registr_success' => $registr_success,
        ]); 
    }
	
	
	
	protected function sendEmail($user)
    {
       /* return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();*/
    }
	
	
	
	
	
	//Получаем все платежные системы
	protected function findPayment()
    {
        if (($model = PaymentSystem::find()->where(['status' => 1])->asArray()->all()) !== null) {
            return $model;
        }
    }
	
	
	   protected function findMod($cat, $reg)
    {
		$reg = Yii::$app->userFunctions->catparent($reg, 'reg');
		$cat = Yii::$app->userFunctions->catparent($cat, 'cat');
		
		$return = CatServices::find()->Where(['cat' => $cat])->asArray()->all();
		foreach ($return as $res) {
           if (in_array($res['reg'], $reg)) {
			   $ret = $res['price'];
		   }
		}		
        if (!isset($ret)) {$ret = '';}			
	    return $ret;
    }
	
		//Добавляем ключ к массиву в куку, чтобы потом при регистрации присвоить объявлению пользователя
		public function expressCookies($key)
		{
		//Получить куку
		$arr = unserialize(Yii::$app->request->cookies['expresskey']);
			if(isset($arr) && $arr) {
				array_push($arr, $key);
			}else{
				$arr[] = $key;
			}
			Yii::$app->response->cookies->add(new \yii\web\Cookie([
				'name' => 'expresskey',
				'value' =>  serialize($arr)
			]));
		}
	
	public  function key($id) {	
		$keys = $id.Yii::$app->security->generateRandomString(3);
		$key = new BlogKey();
		$key->key = $keys;
		$key->blog_id = $id;
		$key->date = date('Y-m-d H:i:s');
		$key->save();
		return $keys;
	 }
	
	

	
	
	protected function findModel($id)
    {
		if (($model = BlogExpress::find()->Where(['id'=> $id])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
	

	
		protected function findModel2($id)
    {
        if (($models = BlogField::find()->Where('message=:message',['message'=> $id])->all()) !== null) {
            foreach($models as $res) {
		     $model3['f_'.$res['field']] = array('value' => $res['value'], 'dop' =>$res['dop']);
		    }
		return $model3;
        }
     }
	 
	 
	 
	 
	 //Формируем массив с файлами в правильном порядке
 function filesDir($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
	$list = Blog::myscandir($dir);	 
if ($list) {	
   foreach($list as $res) {
     $str=strpos($res, "_");
     $row=substr($res, 0, $str);	
     $array[$row] = $res;
    }
   ksort($array);
    foreach($array as $res) {
       $rows[] = $res;
    }
    }	
	if (!isset($rows)) {$rows = '';}
	return $rows;
  } 
	 
	 
	 
	 
	 //Создание папки для файлов при редактировании объявления и перемищения файлов во временные папки для дальнейшей работы с ними 
 protected function findFile($id, $base)
    {

		$url = $id;
		Yii::$app->userFunctions->delDir(Yii::getAlias('@images_temp').'/board/'.$url);
  if ($base) { 
	            @mkdir(Yii::getAlias('@images_temp').'/board/'.$url, 0755);
				@mkdir(Yii::getAlias('@images_temp').'/board/'.$url.'/mini/', 0755);
			    @mkdir(Yii::getAlias('@images_temp').'/board/'.$url.'/maxi/', 0755);
			    @mkdir(Yii::getAlias('@images_temp').'/board/'.$url.'/original/', 0755);
           foreach($base as $key => $res) {
	         @copy(Yii::getAlias('@img').'/board/maxi/'.$res['image'], Yii::getAlias('@images_temp').'/board/'.$url.'/maxi/'.$key.'_'.time().'.jpg');
			 @copy(Yii::getAlias('@img').'/board/mini/'.$res['image'], Yii::getAlias('@images_temp').'/board/'.$url.'/mini/'.$key.'_'.time().'.jpg');
			 @copy(Yii::getAlias('@img').'/board/original/'.$res['image'], Yii::getAlias('@images_temp').'/board/'.$url.'/original/'.$key.'_'.time().'.jpg');
           }

     }
	}







//Выбираем данные пользователя для админа, чтобы было видно какой баланс на счету.
 protected function findUserbalance($id)
    {
	   $model = User::find()->where(['id' => $id])->one();
	   return $model;
	}
	
	
	
	//Выбираем данные пользователя для админа, чтобы было видно какой баланс на счету.
 protected function findUserminus($balance, $user_id, $blog_id)
    {	
          $rates = Rates::find()->where(['def' => 1, 'value' => 1])->asArray()->one();
	      $pay = new Payment();
          $pay->price = -$balance;
		  $pay->currency  = $rates['charcode'];
		  $pay->user_id  = $user_id;
		  $pay->system  = 'Личный счет';
		  $pay->blog_id  = $blog_id;
		  $pay->services  = 'act';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 
		  
		 
		  //Вычитаем стоимость с баланса у юзера
	
		  $user = User::findOne($user_id);
		  $user->balance = $user->balance-$balance;
		  $user->save(false); 
    }
	
	
		//------Обновление координаты----------------//

	protected function findCoord($id)
    {
		if (($model = BlogCoord::find()->where(['blog_id' => $id])->one()) !== null) {
            return $model;
        }
    }
	
	
		//Оповещение о предзаказе
 protected function findOrder($blog)
    {
		$link = Url::to(['/boardone', 'id'=>$blog->id]);
		$orders = Orders::find()->where(['board_id' => $blog->id])->andWhere(['<=','colvo', $blog->count])->all();
		foreach($orders as $order) {
			Yii::$app->functionMail->order_send($order, $blog->title, $blog->count, $link, $blog->shop['domen']);
			$arrid[] = $order->id;
		}
		if(isset($arrid)) {
		   //Orders::deleteAll(['id' => $arrid]);
		}
	}
	

public  function fieldarr($id) {
         $cat = Yii::$app->request->get('cat');
          //Достаем массив категорий, к которым пренадлежит переданная категорий в переменрой $id потом достать все поля этих категорий
		 
             $cat_array = Yii::$app->userFunctions->linens(Yii::$app->caches->category(),$id);
	
             array_unshift($cat_array, "0");
            //Выбираем поля фильтра всех категорий, которым преналдежит $id
            $customers = Field::find()->where(['cat' => $cat_array])->orderBy(['sort' => SORT_ASC])->asArray()->all();

            foreach ($customers as $rows) {
	            if ($rows['type'] == 'p') {
                    $rate = true;
	            }
             }

              return $customers;

            }

}