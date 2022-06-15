<?php
namespace frontend\models;
use common\models\BlogKey;
use common\models\Blog;
use yii\base\Model;
use Yii;
class KeySearch extends Model
{
    public $key;
    /**
     * @var UploadedFile
     */


    public function rules()
    {
        return [
            [['key'], 'required'],
            [['key'], 'string'],
            ['key', 'validateKey'],
        ];
    }


    	
 public function validateKey($attribute, $params, $validator)
 {

         if(!$key = BlogKey::find()->Where(['key' => $this->key])->One()) {
             $this->addError('key', 'Объявление не найдено, такого ключа не существует.');
         }else{
            if($blog = Blog::findOne($key->blog_id)) {
              $blog->user_id = Yii::$app->user->id;
              $blog->update(false);
              $key->delete();
            }else{
              $this->addError('key', 'Что-то не то с этим объявлением.');
            }
         }
 }

 public function attributeLabels()
 {
     return [
         'key' => 'Ключ',
     ];
 }
     
    

}