<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Upload;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
use yii\web\Response;


class UploadController extends Controller
{

    public function actionUpload()
    {
		
		if (Yii::$app->request->isPost) {
            $model = new Upload();

			$model->file = UploadedFile::getInstance($model, 'file');
			if($model->load(Yii::$app->request->post()) && $model->validate() ) {
				
				
	   if ($this->filesId($model->dir) > Yii::$app->caches->setting()['max_photo_count'] ) {
		   $result = [
                    'error' => 'Привышено количество файлов, не более '.Yii::$app->caches->setting()['max_photo_count']
                ];

  Yii::$app->response->format = Response::FORMAT_JSON;			 
			 return  $result;
		}
		
		
            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {	
			    //Нумируем изображение
				//print_r($this->filesKey($model->dir));
	            $count = $this->filesKey($model->dir).'_';
				$dir = Yii::getAlias('@images_temp').'/board/';
			    $dir = $dir.$model->dir.'/';
			    @mkdir($dir, 0700);
				@mkdir($dir.'mini/', 0700);
			    @mkdir($dir.'maxi/', 0700);
			    @mkdir($dir.'original/', 0700);
				
					//Имя файла
                $model->file->name = strtotime('now').'.png';
				
		//Сохраняем файл оригинал		
       if ($model->file->saveAs($dir.'original/' . $count.$model->file->name)) {
		   
		//Берем оригинал и делаем копии
         $imag = Yii::$app->image->load($dir.'original/' . $count.$model->file->name);
		//Сохраняем в папку с большим размером 
		 $imag->background('#FFF','0');
		 $imag->resize(Yii::$app->caches->setting()['big_photo'],null,Yii\image\drivers\Image::INVERSE);
		 $imag->save($dir.'maxi/'.$count.$model->file->name, 90);
				
			

         //Сохраняем в папку с маленьким размером 
	     $imag->background('#FFF','0');
		 $imag->resize(Yii::$app->caches->setting()['min_photo_width'], Yii::$app->caches->setting()['min_photo_height'],Yii\image\drivers\Image::INVERSE);
		 $imag->crop(Yii::$app->caches->setting()['min_photo_width'], Yii::$app->caches->setting()['min_photo_height']);
		 $imag->save($dir.'mini/'.$count.$model->file->name, 90);
		 
		 
		 
		 
			        if (!isset($result_link)) {$result_link = '';}
                    $result = ['filelink' => $result_link . $model->file->name,'filename' => $model->file->name];
                } else {
                    $result = [
                        'error' => Yii::t('vova07/imperavi', 'ERROR_CAN_NOT_UPLOAD_FILE')
                    ];
                }	
			}
			}else{
				
				 $result = [
                    'error' => $model->hasErrors()
                ];
			}
       Yii::$app->response->format = Response::FORMAT_JSON;
           
		   return $result;
        } else {
            throw new BadRequestHttpException('Что-то не так');
        }
  
  
    }
	
	
	
	
	
	
	
	
	
	
	
	
	//Удаление файлов при клике на кнопку удаления в представлении
    public function actionDel()
    {
if (Yii::$app->request->post('key')) {
$file = Yii::$app->request->post('key');

@unlink($file);
@unlink(str_replace('maxi','mini',$file));
@unlink(str_replace('maxi','original',$file));
return true;
}
return false;
    }
	
	
	
	
	
  public function actionSort()
    {
if (Yii::$app->request->post()) {


$dir_mini = Yii::getAlias('@images_temp').'/board/'.Yii::$app->request->get('dir_id').'/mini/';
$dir_maxi = Yii::getAlias('@images_temp').'/board/'.Yii::$app->request->get('dir_id').'/maxi/';
$dir_original = Yii::getAlias('@images_temp').'/board/'.Yii::$app->request->get('dir_id').'/original/';


foreach(Yii::$app->request->post('arr') as $key => $res) {
rename($dir_mini.$res['caption'], $dir_mini.$key.'_'.time().'.png');
rename($dir_maxi.$res['caption'], $dir_maxi.$key.'_'.time().'.png');
rename($dir_original.$res['caption'], $dir_original.$key.'_'.time().'.png');

}
    }	
	}
	
	
//Выгрузка фото из временной папки
 function filesId($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
	$list = @scandir($dir);
	return @count($list)-2;
	} 
	
	
//Достаем Ключ Последнего файла
 function filesKey($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
	$list = @scandir($dir);	 
if (@count($list) > 2) {

   foreach($list as $res) {
     $str=strpos($res, "_");
     $row=substr($res, 0, $str);	
     $array[$row] = $row;
    }
   ksort($array);
    foreach($array as $res) {
       $row = $res;
    }
$row = $row+1;
    }else{
        $row = 0;
    }	
	return $row;
  }

	
}