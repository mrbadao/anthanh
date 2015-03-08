<?php

/**
 * This is the model class for table "content_message".
 *
 * The followings are the available columns in table 'content_message':
 * @property integer $id
 * @property string $title
 * @property string $thumbnail
 * @property integer $content_categories_id
 * @property string $location
 * @property string $price
 * @property string $square
 * @property string $use_square
 * @property string $user_id
 * @property integer $status
 * @property string $detail
 * @property string $created
 * @property string $modified
 */
class ContentMessage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, user_id', 'required'),
			array('content_categories_id, status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('thumbnail', 'length', 'max'=>128),
			array('location, price, square, use_square, user_id', 'length', 'max'=>45),
			array('detail, title, thumbnail, created, location, price, square, use_square, user_id, modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, thumbnail, content_categories_id, location, price, square, use_square, user_id, status, detail, created, modified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Tiêu đề',
			'thumbnail' => 'Thumbnail',
			'content_categories_id' => 'Content Categories',
			'location' => 'Location',
			'price' => 'Price',
			'square' => 'Square',
			'use_square' => 'Use Square',
			'user_id' => 'User',
			'status' => 'Status',
			'detail' => 'Detail',
			'created' => 'Created',
			'modified' => 'Modified',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('thumbnail',$this->thumbnail,true);
		$criteria->compare('content_categories_id',$this->content_categories_id);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('square',$this->square,true);
		$criteria->compare('use_square',$this->use_square,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('detail',$this->detail,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContentMessage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
