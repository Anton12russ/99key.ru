<?php

namespace common\models;
use yii\web\UploadedFile;
use common\models\BlogField;
use common\models\BlogServices;
use common\models\BlogImage;
use common\models\Field;
use common\components\behaviors\StatusBehavior;
use yii\helpers\Url;
use Yii;
$date = (new \DateTime('now', new \DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');
/**
 * This is the model class for table "blog".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property string $url
 * @property int $status_id
 * @property int $date_add
 * @property int $date_update
 * @property string $image
 * @property int $category
 * @property int $region
 * @property int $count

 
 */
class Blog extends \yii\db\ActiveRecord
{
const STATUS_LIST = ['На модерации','Опубликовано','Удалено'];
const STATUS_ACTIVE = ['Ожидает','Оплачен'];
public $file;
public $dir_name;
//Обновление
public $coordlat;
public $coordlon;
public $address;

//public $category_url;
//public $region_url;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog';
    }



    /**
     * {@inheritdoc}
     */
    public function rules()
    {

	if ($this->id) {$date_update = date('Y-m-d H:i:s');}else{$date_update = '';};
        return [
            [['text','title','category','region', 'user_id'], 'required'],
			[['active','category','region', 'user_id', 'views', 'express'], 'integer'],
            [['text', 'dir_name','date_del','coordlat','coordlon','address'], 'string'],
            [['title', 'url'], 'string', 'max' => 150],
			[['date_add'], 'date', 'format'=>'php:Y-m-d H:i:s' ],
			[['date_update'], 'date', 'format'=>'php:Y-m-d H:i:s'],
			[['date_update'], 'default', 'value'=> $date_update],
			[['status_id'], 'default', 'value'=> '1'],
			[['file'], 'image'],
			[['author'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
	// 



	
	
	
	

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'date_add' => 'Дата создания',
			'date_update' => 'Дата редактирования',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'url' => 'Url',
            'status_id' => 'Статус',
			'category' => 'Категория',
			'region' => 'Регион',
			'image' => 'Фото',
			'file' => 'Фото',
			'smallImage' => 'Фото',
			'author' => 'Автор',
			'statusName' => 'Статус',
		    'author.username' => 'Автор',
			'user_id' => 'Пользователя',
			'Status' => 'Статус',
			'date_del' => 'Дата удаления',
			'active' => 'Активировация',
			'Active' => 'Активировация',
			'auk_price' => 'Стоимость резервации',
			'userreservauthor' => 'Информация продавца',
			'pricepay' => 'Стоимость выкупа'
        ];
    }
		//Связь с Blog_image на главной во фронте
	public function getAuctions() {
    return $this->hasOne(BlogAuction::className(),['blog_id'=>'id']);
	}		
		//---Обновление координаты ---//
	//Связь с Таблицей координат
    public function getCoord() {
       return $this->hasOne(BlogCoord::className(),['blog_id'=>'id']);
	}
	//Связь с Платными услугами
    public function getServices() {
    return $this->hasMany(BlogServices::className(),['blog_id'=>'id']);
	}
	
    //Связь с Автором
	public function getAuthor() {
    return $this->hasOne(User::className(),['id'=>'user_id']);
	}
	//Связь с Регионом
	public function getRegions() {
    return $this->hasOne(Region::className(),['id'=>'region']);
	}
   //Связь с Категорией
	public function getCategorys() {
    return $this->hasOne(Category::className(),['id'=>'category']);
	}
    //Связь с Blog_field
	public function getBlogField() {
    return $this->hasMany(BlogField::className(),['message'=>'id']);
	}
	
	//Связь с Blog_field
	public function getField() {
    return $this->hasMany(Field::className(),['id'=>'field'])->via('blogField');
	}
	public function getReservuser() {
       return $this->hasOne(User::className(),['id'=>'reserv_user_id']);
	}
	//Связь с Blog_image
	public function getImageBlog() {
    return $this->hasMany(BlogImage::className(),['blog_id'=>'id']);
	}

	
		//Связь с Blog_image на главной во фронте
	public function getImageBlogOne() {
    return $this->hasOne(BlogImage::className(),['blog_id'=>'id']);
	}
	
	
	
	public function getSmallImage() {
		if($this->image) {
	      $path = str_replace('admin/','',Url::home(true)).'uploads/images/board/50x50/'.$this->image;
	    }else{
		  $path = str_replace('admin/','',Url::home(true)).'uploads/images/no-photo.png';
	    }
	return $path;
	}
	


	 
		 //Выгрузка фото из временной папки
 static function files($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
	$files = @Blog::myscandir($dir);
	if ($files) {
	foreach($files as $res) {
$str=strpos($res, "_");
$row=substr($res, 0, $str);

$files_res[$row] = '/uploads/temp/images/board/'.$dir_name.'/maxi/'.$res;

	}

	ksort($files_res);
	foreach($files_res as $res) {
	$files_ress[] = $res;	
	}

	    return $files_ress;
	}else{
		 return '';
	}
	} 



//Выгрузка фото из временной папки
static function previewconfig($dir_name) {

	$dir = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
	$files = @Blog::myscandir($dir);
	if ($files ) {
		
	
foreach($files as $res) {
$str=strpos($res, "_");
$row=substr($res, 0, $str);	
$array[$row] = array('caption'=>$res, 'size' => filesize($dir.$res), 'key'=>$dir.$res);
}
	
		ksort($array);
	foreach($array as $res) {
	$files_arr[] = $res;	
	}

	    return $files_arr;
	}else{
		 return '';
	}
	} 
	 
static function myscandir($dir, $sort=0)
{
	$list = @scandir($dir, $sort);
	
	// если директории не существует
	if (!$list) return false;
	
	// удаляем . и .. (я думаю редко кто использует)
	if ($sort == 0) unset($list[0],$list[1]);
	else unset($list[count($list)-1], $list[count($list)-1]);
	return $list;
}



	


static function arrayche($arr) {
  foreach(explode("\n", $arr) as $key => $val) {
        $array[$key] = ($val);
		}
		return $array;
}	

static function arrayval($arr) {
	   foreach(explode("\n", $arr) as $key => $val) {
        $array[] = ($val);
		}
		return $array;
	}

static function req($arr) {
	   if ($arr == 1) {
		   return '<span class="req_val">*</span>';
	   }
	}
static function arrayrates($arr) {
        foreach($arr as $res) {
        $rates[$res['id']] = $res['name'];
		}
		return $rates;
	}
	
	//Статус
	public function getStatus() {
    $arrey = ['0'=>['На модерации'],'1'=>['Опубликовано'],'2'=>['Удалено']];
	return $arrey[$this->status_id][0];	
	}
	
		//Статус
	public function getActive() {
    $arrey = ['0'=>['Ожидает'],'1'=>['Активировано']];
	return $arrey[$this->active][0];	
	}	
}

