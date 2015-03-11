<?php

/**
 * This is the model class for table "content_setting".
 *
 * The followings are the available columns in table 'content_setting':
 * @property integer $id
 * @property string $fb
 * @property string $tw
 * @property string $yotube
 * @property string $intro_video
 * @property string $skype
 * @property string $img_1
 * @property string $img_2
 * @property string $img_3
 */
class ContentSetting extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fb, tw, yotube, intro_video, skype', 'required'),
			array('fb, tw, yotube, intro_video, skype, img_1, img_2, img_3', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fb, tw, yotube, intro_video, skype, img_1, img_2, img_3', 'safe', 'on'=>'search'),
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
			'fb' => 'Fb',
			'tw' => 'Tw',
			'yotube' => 'Yotube',
			'intro_video' => 'Intro Video',
			'skype' => 'Skype',
			'img_1' => 'Img 1',
			'img_2' => 'Img 2',
			'img_3' => 'Img 3',
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
		$criteria->compare('fb',$this->fb,true);
		$criteria->compare('tw',$this->tw,true);
		$criteria->compare('yotube',$this->yotube,true);
		$criteria->compare('intro_video',$this->intro_video,true);
		$criteria->compare('skype',$this->skype,true);
		$criteria->compare('img_1',$this->img_1,true);
		$criteria->compare('img_2',$this->img_2,true);
		$criteria->compare('img_3',$this->img_3,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContentSetting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
