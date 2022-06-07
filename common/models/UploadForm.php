<?php
namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $url;
    public function rules()
    {
	
        return [
		 
			['url', 'url'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
			
        ];
    }
	

	
	
    
    public function upload()
    {
        if ($this->validate()) {

            $this->imageFile->saveAs($_SERVER['DOCUMENT_ROOT'].'/uploads/images/shop/slider/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
	
	
	
	
	
	 public function attributeLabels()
    {
        return [
            'imageFile' => 'Фото',
            'url' => 'Ссылка',
        ];
    }
}