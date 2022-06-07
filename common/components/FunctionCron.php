<?php
 
namespace common\components;
use yii\helpers\Url;
use yii\base\Component;
use yii\helpers\Html;
use common\models\Cron;
use common\models\Blog;
use common\models\Payment;
use common\models\Car;
use common\models\Shop;
use common\models\User;
use common\models\Bet;
use common\models\BlogServices;
use yii\web\NotFoundHttpException;
use yii;

class FunctionCron extends Component { 	
    public function general() 
	{	
	   $model = $this->findModel(1);
	   $time = Yii::$app->caches->setting()['cron']*60;
	   $time = time()-$time;
//$this->findBlogtest();
           $this->findBlog();
	  if($model->time <= $time) {
		   //Сюда вставить код, который будет ......
           $this->findBlog();
		   $this->findCar();
		   $this->findBlogServices();
		   $this->findShop();
		   $this->delDir(Yii::getAlias('@images_temp').'/board/');
		   $this->delDir(Yii::getAlias('@images_temp').'/shop/');
		   $model->time = time();
	       $model->save(false);
		    return true;
	    }else{
			return false;
		}
	}	





    protected function findShop()
    {
	    if($mail = Shop::find()->andWhere(['active'=> 1])->andWhere(['<=','date_end', date('Y-m-d H:i:s')])->all()) {
	         foreach($mail as $one) {
					$url = Url::to(['/shop/one', 'region'=>$one->regions['url'], 'category'=>$one->categorys['url'], 'id'=>$one->id, 'name'=>Yii::$app->userFunctions->transliteration($one->name), 'general' => 'true']);
					Yii::$app->functionMail->shop($one->id, $one->author, $url, $one->name);
	         }
           
	      $count = Shop::updateAll(
	        ['active' => '0'],
	        ['AND', ['=', 'active', 1],['<=','date_end', date('Y-m-d H:i:s')]]);
	     }
	}
	
	
	
    protected function findCar()
    {
		
		foreach(Yii::$app->caches->rates() as $rat) {
				 if($rat['def'] == 1) {$rate = $rat;}
		}
		$status = array('0','1','2');
	    if($mail = Car::find()->andWhere(['status'=> $status, 'dispute'=>0])->andWhere(['<=','data_add', date('Y-m-d H:i:s', strtotime(' - 30 day'))])->all()) {
	     
			 foreach($mail as $one) {
				 Yii::$app->functionMail->carbayer($one->id, $one->bayer['name'], $one->bayer['email'], 4);
				 Yii::$app->functionMail->carshop($one->id, $one->user['username'], $one->user['email'], 4);
				 $id_update[] = $one->id;
				 
			   if($one->pay == 1) {
				  $user = User::findOne($one->user_id);
				  $user->balance = $user->balance+$one->price;
				  $user->update(false);
				  
				  $user2 = User::findOne($one->bayer);

				  if(isset($user2->balance_temp) && $user2->balance_temp >= 0) {
				    $user2->balance_temp = $user2->balance_temp-$one->price;
					$user2->update(false);
				  }
				  
				  
		  $pay = new Payment();
          $pay->price = +$one->price;
		  $pay->currency  = $rate['code'];
		  $pay->user_id  = $one->user_id;
		  $pay->system  = 'Продажа № '.$one->id;
		  $pay->blog_id  = '';
		  $pay->services  = 'shop';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 
				

			   }
	         }
			 
           if(isset($id_update)) {
	       $count = Car::updateAll(
	        ['status' => '4'],
	        ['id'=>$id_update]);
		   }
	     }
	}
/*
    protected function findBlogtest()
    {
	    if($mail = Blog::find()->andWhere(['id'=> 3081])->all()) {
	         foreach($mail as $board) {
				// echo date($board->date_del, strtotime("+2 days"));
					$url = Url::to(['/blog/one', 'region'=>$board->regions['url'], 'category'=>$board->categorys['url'], 'url'=>$board->url, 'id'=>$board->id, 'general' => 'true']);
					Yii::$app->functionMail->board($board->id, $board->author, $url, $board->title);
	         
			   if($board->auction == 1) {
				      $bets = Bet::find()->andWhere(['blog_id'=>$board->id])->limit(1)->orderBy(['id' => SORT_DESC])->All();
					  if(isset($bets[0]->user_id)) {
				      $blog = Blog::findOne($board->id);
					  $blog->reserv_user_id = $bets[0]->user_id;
					  $blog->auction = 3;
					  $blog->update();
					
			           //Емэйл сообщение
				 foreach($board->blogField as $field) {
                    if($field['field'] == '475') {
	                 $phone = $field['value'];
                    }
                 }    
		  $email_to = $bets[0]->author['email'];

		  $textmail = ' <table>
		  <tbody>
          <tr>
            <td><strong>Имя:</strong></td>
            <td>'.$board->author['username'].'</td>

          </tr>
          <tr>
            <td><strong>Email</strong></td>
               <td>'.$board->author['email'].'</td>
          </tr>
		  
		  <tr>
		       <td><strong>Телефон</strong></td>
               <td>'.$phone.'</td>
          </tr>
		  
		  <tr>
		       <td><strong>Адрес</strong></td>
               <td>'.$board->coord['text'].'</td>
          </tr>

        </tbody>
      </table>
		  ';
		  
		  
		   $text = 'Вы выиграли в торгах на лот "'.$board->title;
		   Yii::$app->userFunctions3->Push($board->author['id'], $text, 'https://1tu.ru/user/successlot');
		  
		  Yii::$app->functionMail->lotwinning($email_to, $url,  $textmail,  $bets[0]->author['username'], $board);
			   }
			   }
			 }
           
	      $count = Blog::find()->where(['id'=> 3081])->One();
		   $count->status_id = 2;
		   $count->update(false);
	      }
	}
*/
    protected function findBlog()
    {
	    if($mail = Blog::find()->andWhere(['status_id'=> 1])->andWhere(['<=','date_del', date('Y-m-d H:i:s')])->all()) {
	         foreach($mail as $board) {
				// echo date($board->date_del, strtotime("+2 days"));
					$url = Url::to(['/blog/one', 'region'=>$board->regions['url'], 'category'=>$board->categorys['url'], 'url'=>$board->url, 'id'=>$board->id, 'general' => 'true']);
				 if($board->auction == 1) {
					 Yii::$app->functionMail->auction($board->id, $board->author, $url, $board->title);
				 }else{
					Yii::$app->functionMail->board($board->id, $board->author, $url, $board->title);
	             }
			   if($board->auction == 1) {
				      $bets = Bet::find()->andWhere(['blog_id'=>$board->id])->limit(1)->orderBy(['id' => SORT_DESC])->All();
					  if(isset($bets[0]->user_id)) {
						
				      $blog = Blog::findOne($board->id);
					  $blog->reserv_user_id = $bets[0]->user_id;
					  $blog->auction = 3;
					  $blog->update();
					
			           //Емэйл сообщение
				 foreach($board->blogField as $field) {
                    if($field['field'] == '475') {
	                 $phone = $field['value'];
                    }
                 }    
		  $email_to = $bets[0]->author['email'];

		  $textmail = ' <table>
		  <tbody>
          <tr>
            <td><strong>Имя:</strong></td>
            <td>'.$board->author['username'].'</td>

          </tr>
          <tr>
            <td><strong>Email</strong></td>
               <td>'.$board->author['email'].'</td>
          </tr>
		  
		  <tr>
		       <td><strong>Телефон</strong></td>
               <td>'.$phone.'</td>
          </tr>
		  
		  <tr>
		       <td><strong>Адрес</strong></td>
               <td>'.$board->coord['text'].'</td>
          </tr>

        </tbody>
      </table>
		  ';
		  
		  
		   $text = 'Вы выиграли в торгах на лот "'.$board->title;
		   Yii::$app->userFunctions3->Push($board->author['id'], $text, 'https://1tu.ru/user/successlot');
		  
		  Yii::$app->functionMail->lotwinning($email_to, $url,  $textmail,  $bets[0]->author['username'], $board);
			   }
			   
			   
			          $blog = Blog::findOne($board->id);
					  $blog->auction = 3;
					  $blog->update(false);
			   }
			 }
           
	      $count = Blog::updateAll(
	        ['status_id' => '2'],
	        ['AND', ['=', 'status_id', 1],['<=','date_del', date('Y-m-d H:i:s')]]);
	      }
	}
	
	

	
	//Удаляем все папки и файлы, которые старше чем 2 дня
	protected function delDir($patch)
    {
         $dir = $patch; /** define the directory **/
         $dir =(glob($dir."*"));
         if($dir) {
		
           foreach ($dir as $file) {
                if (filemtime($file) < time()-86400) { // 2 days
                    Yii::$app->userFunctions->delDir($file);
                }
             }
	      }
	}
	
	
	
	
    protected function findBlogServices()
    {
     	 BlogServices::deleteAll(['<=','date_end', date('Y-m-d H:i:s')]);
	     Yii::$app->cache->delete('services');
	}





    protected function findModel($id)
    {
        if (($model = Cron::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	}
	                                                                                                                                                                                                                        