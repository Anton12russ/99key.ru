<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use frontend\models\Shop;
use frontend\models\User;
use common\models\Rates;
use common\models\Payment;
use common\models\ShopField;
use common\models\ShopImage;
use yii\web\NotFoundHttpException;
class ShopUpdate extends Action
{
    public function run($id)
    {
     if(Yii::$app->user->isGuest) {
		 return $this->controller->redirect(['user/index']);
	 }

		$rates = Rates::find()->where(['def' => 1, 'value' => 1])->asArray()->one();
		$dir_name = Yii::$app->security->generateRandomString(5).'_'.time();
        $model = $this->findModel($id);
	     if($model->user_id != Yii::$app->user->id) {	
		     //Проверяем права на редактирование
		      if(!Yii::$app->user->can('updateOwnPost', ['shop' => $model]) && !Yii::$app->user->can('updateShop')) {
		        	 throw new NotFoundHttpException('The requested page does not exist.'); 
		      }
		 }
		 
		 
		 
		   $dir_name = $model->id;
		   $model2 = $this->findModel2($model->id);
		   
	       if (!Yii::$app->request->post()) {
	           $base = ShopImage::find()->where(['shop_id'=>$model->id])->select(['image'])->asArray()->all();
			   if(isset($model->img)){$logo = $model->img;}else{$logo = '';}
	           $this->findFile($model->id, $model2['price'], $logo);
			  
		   }
		  
	      

		  
		  //Манипуляции с графиком
	      $grafik_post = explode(' | ',$model2['grafik']);
		  foreach($grafik_post as $key => $res) {
			  if ($res == 'False') {
			       $model->grafik_arr[$key]['vih'] = 1;
			  }else{
				   $obed = explode(' && ', $res); //Обед

				    if(isset($obed[1]) && $obed[1] != 'obed_none') {
                         $obed_arr =  explode(' - ',$obed[1]);

						$model->grafik_arr[$key]['obed-on'] = $obed_arr[0];
						$model->grafik_arr[$key]['obed-do'] = $obed_arr[1];
					}
					$days = explode(' && ', $res);
					$days = explode(' - ', $days[0]); 
					if(isset($days[1])) {
				       $model->grafik_arr[$key]['ot'] = $days[0];
				       $model->grafik_arr[$key]['do'] = $days[1];
				   }
			  }
		  }

		  
		  $model['attributes'] = $model2;


	      $meta['title'] = 'Обновление магазина';$meta['h1'] = $meta['title'];$meta['keywords'] = 'магазин, обновление'; $meta['description'] = 'Редактор магазина'; $meta['breadcrumbs'] = 'Обновить магазин';
	       

        if ($model->load(Yii::$app->request->post())) {
	        if ($model->dir_name) {$dir_name = $model->dir_name;}
			
			//для графика работы
		  $graf = '';
          foreach($model->grafik_arr as $key => $res) {
               if($model->grafik_arr[$key]['vih']) {$graf .= 'False | ';}else{
               if($model->grafik_arr[$key]['ot']) {$graf .= $model->grafik_arr[$key]['ot']. ' - ';}else{$graf .= '08:00 - ';}
               if($model->grafik_arr[$key]['do']) {$graf .= $model->grafik_arr[$key]['do']. ' && ';}else{$graf .= '18:00 && ';}
               if($model->grafik_arr[$key]['obed-on']) {$graf .= $model->grafik_arr[$key]['obed-on']. ' - '. $model->grafik_arr[$key]['obed-do']. ' | ';}else{$graf .= 'obed_none | ';}
			  }
          }	
			
			
			$model->grafik = $graf; 

			//$model->date_add = date('Y-m-d H:i:s');
			//$model->date_end = date('Y-m-d H:i:s', strtotime(' + '.Yii::$app->caches->setting()['end-shop'].' day'));
			if(!Yii::$app->user->can('updateShop')) {
			   $model->status = Yii::$app->caches->setting()['moder-shop'];
			}
			//$model->user_id = Yii::$app->user->id;
            //$model->active = 1; 		
		    $model->balance = true; 
		  
	 if ($model->validate()) {

	  //Переносим логотип
	 $logo_dir = '/shop/'.$dir_name.'/logo-mini/';
	 $logo = Yii::$app->userFunctions->filesDirshop($logo_dir);
	 $dir_logo = Yii::getAlias('@images_temp').'/shop/'.$dir_name.'/logo-mini/';
	 $dir_copy_logo = Yii::getAlias('@img').'/shop/logo/'; 
	 
	 if ($logo) 
		{
            @rename($dir_logo.$logo[0], $dir_copy_logo.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->name).'.jpg');
		    $model->img = $model->id.'_'.Yii::$app->userFunctions->transliteration($model->name).'.jpg';
			     
	    }else{
			     //Удаляем лого, если его уже нет
                   @unlink($dir_copy_logo.$model->img);
				 //-------------------------------------//
				$model->img =''; 
		}
//Вычитаем указанную стоимость из баланса пользователя и создаем выписку
	 if(Yii::$app->user->can('updateShop') && $model->balance_minus) {
	    $this->findUserminus($model->balance_minus, $model->user_id, $rates);
     }
	 
	 
	 
//---------------------- Счетчик, проверяем нужно ли считать---------------------------//

    $model_old = $this->findModel($id);

		
//---------------------- Конец счетчика-----------------------------//	 
	 
	 $model->save();
	 
	 
	 ShopField::deleteAll('shop_id = :shop_id', [':shop_id' => $model->id]);
	 $field = new ShopField();
	 
	       //Переносим файл
	 $file_dir = '/shop/'.$dir_name.'/file/';
	 $file = Yii::$app->userFunctions->filesDirshop($file_dir);
     
	 if ($file) 
		{
			
		$extension = explode('.',$file[0]);
			$dir_file = Yii::getAlias('@images_temp').'/shop/'.$dir_name.'/file/';
		    $dir_copy_file = Yii::getAlias('@img').'/shop/file/';
			@unlink($dir_copy_file.$model->file);
		    foreach($file as $key => $file_ok) {
               rename($dir_file.$file_ok, $dir_copy_file. $dir_name.'_'.Yii::$app->userFunctions->transliteration($model->name).'_'.$key.'.'.$extension[1]);
		       $field->price = $dir_name.'_'.Yii::$app->userFunctions->transliteration($model->name).'_'.$key.'.'.$extension[1];
		    } 
	    }elseif(isset($model->file)){
			$dir_file = Yii::getAlias('@images_temp').'/shop/'.$dir_name.'/file/';
		    $dir_copy_file = Yii::getAlias('@img').'/shop/file/';
			unlink($dir_copy_file.$model->file);
			$field->price = '';
		}
 
	 
     $field->shop_id = $model->id;
	 $field->payment = $model->payment;
	 $field->delivery = $model->delivery;
	 $field->grafik = $model->grafik;	 
	 $field->stocks = $model->stocks;	 
	 $field->address = $model->address;	 
	 $field->coord = $model->coord;		 
	 $field->phone = $model->phone;	 
	 $field->pay_delivery = $model->pay_delivery;	 
	 $field->site = $model->site;	
     $field->private_payment = $model->private_payment;		
     $field->сhoice_pay = $model->сhoice_pay;		 
	 $field->save(false);
	 
	 //Копируем фото и логотип, затем удаляем временную папку со всемми фото
	 $dirict = '/shop/'.$dir_name.'/maxi/';
	 $list = Yii::$app->userFunctions->filesDirshop($dirict);
	 
	 
	 //--------------Удаляем фото, которые были до редактирования-----------------//
	$base_img = ShopImage::find()->where(['shop_id'=>$model->id])->select(['image'])->asArray()->all();
	if($base_img) {
	                $dir_copy_maxi = Yii::getAlias('@img').'/shop/maxi/';
		            $dir_copy_mini = Yii::getAlias('@img').'/shop/mini/';
					//print_r($base_img);
		foreach($base_img as $res) {
			       @unlink($dir_copy_maxi.$res['image']);
				   @unlink($dir_copy_mini.$res['image']);

		}
	}
	 	if ($list) 
		{

			ShopImage::deleteAll('shop_id = :shop_id', [':shop_id' => $model->id]);
			$dir_maxi = Yii::getAlias('@images_temp').'/shop/'.$dir_name.'/maxi/';
		    $dir_mini = Yii::getAlias('@images_temp').'/shop/'.$dir_name.'/mini/';
			$dir_copy_maxi = Yii::getAlias('@img').'/shop/maxi/';
		    $dir_copy_mini = Yii::getAlias('@img').'/shop/mini/';
		
		   foreach($list as $key => $file) {
            @rename($dir_maxi.$file, $dir_copy_maxi.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->name).'_'.$key.'.jpg');
			@rename($dir_mini.$file, $dir_copy_mini.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->name).'_'.$key.'.jpg');
			//@rename($dir_original.$file, $dir_copy_original.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.jpg');
			
			//Сохраняем название файла в базу данных
			       $shopimage = new ShopImage();
				   $shopimage->id = '';
                   $shopimage->shop_id = $model->id;
                   $shopimage->image  = $model->id.'_'.Yii::$app->userFunctions->transliteration($model->name).'_'.$key.'.jpg';
                   $shopimage->save();
		   }
		   
	    }

	 		//Очищаем временную папку с фото и логотипом
          Yii::$app->userFunctions->recursiveRemove(Yii::getAlias('@images_temp').'/shop/'.$dir_name.'/');	
          return $this->controller->redirect(['update', 'save' => 'true', 'id' => $id]);
	      
	   }else{

		   $print[] = $model->errors; 

	   }
	 }  

	if (!isset($print)) {$print = '';}
 
    if ($model->dir_name) {$dir_name = $model->dir_name;}
		 
		 
  if(Yii::$app->user->can('updateShop')) {
	   $user = $this->findUserbalance($model->user_id);
  }else{
	   $user = ''; 
  }
    	 
		 
		 
		 


        return $this->controller->render('update', [
            'model' => $model,
			'user' => $user,
			'meta' => $meta,
			'dir_name' => $dir_name,
			'print' => $print,
			'rates' => $rates,

        ]);
	}
	
	
	
	
		protected function findModel($id)
    {
        if (($model = Shop::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        }

      throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	protected function findModel2($id)
    {
        if (($model2 = ShopField::find()->where(['shop_id' => $id])->asArray()->one()) !== null) {
            return $model2;
        }

     //   throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	

	
//Выбираем данные пользователя для админа, чтобы было видно какой баланс на счету.
 protected function findUserminus($balance, $user_id, $rates)
    {	

	      $pay = new Payment();
          $pay->price = -$balance;
		  $pay->currency  = $rates['charcode'];
		  $pay->user_id  = $user_id;
		  $pay->system  = 'Личный счет';
		  $pay->blog_id  = '';
		  $pay->services  = 'shop';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 
		  
		 
		  //Вычитаем стоимость с баланса у юзера
	
		  $user = User::findOne($user_id);
		  $user->balance = $user->balance-$balance;
		  $user->save(false); 
    }
	
//Выбираем данные пользователя для админа, чтобы было видно какой баланс на счету.
 protected function findUserbalance($id)
    {
	   $model = User::find()->where(['id' => $id])->one();
	   return $model;
	}
	
	
	
	
		 //Создание папки для файлов при редактировании объявления и перемищения файлов во временные папки для дальнейшей работы с ними 
 protected function findFile($id, $file, $logo)
    {
		
		$url = $id;
		Yii::$app->userFunctions->delDir(Yii::getAlias('@images_temp').'/shop/'.$url);
       if ($file || $logo) { 
	   
	        @mkdir(Yii::getAlias('@images_temp').'/shop/'.$url, 0775);

            if($logo) {

				@mkdir(Yii::getAlias('@images_temp').'/shop/'.$url.'/logo-mini/', 0775);
				@copy(Yii::getAlias('@img').'/shop/logo/'.$logo, Yii::getAlias('@images_temp').'/shop/'.$url.'/logo-mini/'.$logo);
				
			}
			
			if($file) {
				@mkdir(Yii::getAlias('@images_temp').'/shop/'.$url.'/file/', 0775);
				@copy(Yii::getAlias('@img').'/shop/file/'.$file, Yii::getAlias('@images_temp').'/shop/'.$url.'/file/'.$file);
			}
 

     }
	}
}