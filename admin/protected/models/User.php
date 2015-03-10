<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $loginid
 * @property string $password
 * @property string $name
 * @property integer $is_super
 * @property string $phone
 * @property string $email
 * @property string $created
 * @property string $modified
 */
class User extends CActiveRecord
{
//	public $new_pwd='';
//	public $confirm_pwd='';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('loginid, password, name, phone, address, email', 'required'),
			array('is_super', 'numerical', 'integerOnly'=>true),
			array('email','email'),
			array('password', 'match', 'pattern'=>'/^[!-~]+$/'),
			array('password', 'checkPassword', 'on'=>'check_password'),
			array('loginid', 'length', 'max'=>20),
			array('password', 'length', 'max'=>128),
			array('name, email', 'length', 'max'=>100),
			array('phone', 'length', 'max'=>15),
			array('created, modified, address, name', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loginid, password, name, is_super, phone, email, created, modified, address', 'safe', 'on'=>'search'),
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

	public function checkPassword($attr,$params){
		if (!$_POST['new_pwd'])
		{
			$this->addError('new_pwd','Vui lòng nhập mật khẩu mới.');
		}
		else if (!preg_match("/^[!-~]+$/", $_POST['new_pwd']))
		{
			$this->addError('new_pwd','Mật khẩu mới vui lòng nhập các ký tự chữ và các biểu tượng.');
		}
		else
		{
			$length = strlen($_POST['new_pwd']);
			if(($length < 6) || ($length > 16)) {
				$this->addError('new_pwd','Mật khẩu mới Vui lòng nhập trong vòng 6-16 ký tự.');
			}
		}

		if(!$_POST['confirm_pwd'])
		{
			$this->addError('confirm_pwd','Vui lòng nhập mật khẩu mới (xác nhận).');
		}
		else if (!preg_match("/^[!-~]+$/", $_POST['confirm_pwd']))
		{
			$this->addError('confirm_pwd','Mật khẩu mới (kiểm tra) xin vui lòng nhập các ký tự chữ và số và ký hiệu.');
		}
		else
		{
			$length = strlen($_POST['confirm_pwd']);
			if(($length < 6) || ($length > 16)) {
				$this->addError('confirm_pwd','Mật khẩu mới (kiểm tra) Vui lòng nhập trong vòng 6-16 ký tự');
			}
		}

		if($_POST['new_pwd'] !== $_POST['confirm_pwd'])
		{
			$this->addError('confirm_pwd','Mật khẩu mới và mật khẩu xác nhận không khớp.');
		}

		if($this->hashPassword($_POST['user']['password']) !== $this->password)
		{
			$this->addError('password','Mật khẩu hiện tại không đúng.');
		}
	}

	public function hashPassword($pswd)
	{
		return md5($pswd);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'loginid' => 'Loginid',
			'password' => 'Mật khuẩu',
//			'new_pwd' => 'Mật khẩu mới',
//			'confirm_pwd' => 'Mật Khẩu xác nhận',
			'name' => 'Tên hiển thị',
			'is_super' => 'Is Super',
			'phone' => 'Phone',
			'email' => 'Email',
			'address' => 'Địa chỉ',
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
		$criteria->compare('loginid',$this->loginid,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('is_super',$this->is_super);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
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
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
