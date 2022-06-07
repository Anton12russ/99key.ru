<?php

namespace common\models;
use yii\web\UploadedFile;
use Yii;
use yii\base\Model;

class Upload extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;
	public $price;
	public $dir;
    public function rules()
    {

        return [
		    [['dir'], 'required'],
            [['file'], 'image'],
			[['price'], 'file'],
			[['dir'], 'string'],
        ];
    }
	
	
	
    
}
