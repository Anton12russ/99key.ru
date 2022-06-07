<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use yii\helpers\Url;
use frontend\models\Shop;
use frontend\models\User;
use common\models\Rates;
use common\models\Payment;
use common\models\ShopField;
use common\models\ShopImage;
class ShopAdd extends Action
{
    public function run()
    {
		 if (Yii::$app->user->isGuest) {
			  return $this->controller->redirect(['/user']);
		 }
		$rates = Rates::find()->where(['def' => 1, 'value' => 1])->asArray()->one();
		$dir_name = Yii::$app->security->generateRandomString(5).'_'.time();
		if (!isset($_GET['save'])) {
		   $model = $this->findModel(Yii::$app->user->id);
		 
		   if ($model) {
	           return $this->controller->redirect(['update', 'id' => $model->id]);
	       }
	
		}
	      $model = new Shop();
		   
	      $meta['title'] = 'Регистрация магазина';$meta['h1'] = $meta['title'];$meta['keywords'] = 'магазин, регистрация'; $meta['description'] = 'Добавить магазин'; 


     //Хлебные крошки
	if (Yii::$app->request->cookies['region']) {
	   $reg_url = '/'.Yii::$app->caches->region()[Yii::$app->request->cookies['region']->value]['url'];
	}else{
       $reg_url = '';
	}
		  $meta['breadcrumbs'][] = array('label'=> 'Магазины', 'url' => $reg_url.'/shop/');
		  $meta['breadcrumbs'][] = array('label'=> $meta['title']);
	       

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
			$model->date_add = date('Y-m-d H:i:s');
			$model->date_end = date('Y-m-d H:i:s', strtotime(' + '.Yii::$app->caches->setting()['end-shop'].' day'));
			$model->status = Yii::$app->caches->setting()['moder-shop'];
			$model->user_id = Yii::$app->user->id;
            $model->active = 1; 			
		    $model->balance = true; 
		
	 if ($model->validate()) {
     //Добавляем выписку по счету
     $this->findPay($rates);
	 
	  //Переносим логотип
	 $logo_dir = '/shop/'.$dir_name.'/logo-mini/';
	 $logo = Yii::$app->userFunctions->filesDirshop($logo_dir);
	 if ($logo) 
		{
			$dir_logo = Yii::getAlias('@images_temp').'/shop/'.$dir_name.'/logo-mini/';
		    $dir_copy_logo = Yii::getAlias('@img').'/shop/logo/';
		    foreach($logo as $key => $file) {
               rename($dir_logo.$file, $dir_copy_logo.Yii::$app->user->id.'_'.Yii::$app->userFunctions->transliteration($model->name).'_'.$key.'.jpg');
		       $model->img = Yii::$app->user->id.'_'.Yii::$app->userFunctions->transliteration($model->name).'_'.$key.'.jpg';
		    } 
	    }
	
	 
	 $model->save();
	 //Прибавляем в сетчик

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
	 $field->pay_delivery = $model->pay_delivery;	
	 $field->phone = $model->phone;	 
	 $field->site = $model->site;	
     $field->private_payment = $model->private_payment;		
     $field->сhoice_pay = $model->сhoice_pay;		 
	 $field->save(false);
	 
	 //Копируем фото и логотип, затем удаляем временную папку со всемми фото
	 $dirict = '/shop/'.$dir_name.'/maxi/';
	 $list = Yii::$app->userFunctions->filesDirshop($dirict);
	 	if ($list) 
		{

			//ShopImage::deleteAll('blog_id = :blog_id', [':blog_id' => $model->id]);
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
		  
		  		  //-------------------------Отправка оповещение админу-----------------------------//
			  if(Yii::$app->caches->setting()['email_shop']) {
				  $url = Url::to(['shop/one', 'region'=>$model->regions['url'], 'category'=>$model->categorys['url'], 'id'=>$model->id, 'name'=>Yii::$app->userFunctions->transliteration($model->name)]);
			      Yii::$app->functionMail->shop_admin($url, Yii::$app->user->identity->email, Yii::$app->caches->setting()['email_shop']);
			  }
		  
		  
          return $this->controller->redirect(['add', 'save' => $model->id]);
	      
	   }else{

		   $print[] = $model->errors; 

	   }
	 }  

	if (!isset($print)) {$print = '';}
 
    if ($model->dir_name) {$dir_name = $model->dir_name;}
		 
		 
  
    	 
        return $this->controller->render('add', [
            'model' => $model,
			'meta' => $meta,
			'dir_name' => $dir_name,
			'print' => $print,
			'rates' => $rates,

        ]);
	}
	
	
	
	
		protected function findModel($id)
    {
        if (($model = Shop::find()->where(['user_id' => $id])->one()) !== null) {
            return $model;
        }

     //   throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	
	protected function findPay($rates)
    {
		  $pay = new Payment();
          $pay->price = -Yii::$app->caches->setting()['price-shop'];
		  $pay->currency  = $rates['charcode'];
		  $pay->user_id  = Yii::$app->user->id;
		  $pay->system  = 'Личный счет';
		  $pay->blog_id  = '';
		  $pay->services  = 'shop';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 
		  
		  //Вычитаем стоимость с баланса у юзера
		  $user = User::findOne(Yii::$app->user->id);
		  $user->balance = $user->balance-Yii::$app->caches->setting()['price-shop'];
		  $user->save(false); 
	}
}