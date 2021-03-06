<?php

/**
 * This is the model class for table "content_projects".
 *
 * The followings are the available columns in table 'content_projects':
 * @property integer $id
 * @property string $title
 * @property string $sub_title
 * @property string $thumbnail
 * @property string $detail
 * @property integer $status
 * @property integer $pr
 * @property integer $highline
 */
class ContentProjects extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content_projects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, sub_title', 'required'),
			array('status, pr, highline', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('thumbnail', 'length', 'max'=>128),
			array('title, detail, sub_title, created, modified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, sub_title, thumbnail, detail, status, pr, highline', 'safe', 'on'=>'search'),
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
			'sub_title' => 'Tiêu đề phụ',
			'thumbnail' => 'Thumbnail',
			'detail' => 'Detail',
			'status' => 'Status',
			'pr' => 'Pr',
			'highline' => 'Highline',
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
		$criteria->compare('sub_title',$this->sub_title,true);
		$criteria->compare('thumbnail',$this->thumbnail,true);
		$criteria->compare('detail',$this->detail,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('pr',$this->pr);
		$criteria->compare('highline',$this->highline);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContentProjects the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
