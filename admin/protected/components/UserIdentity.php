<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	private $_role;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$c = new CDbCriteria();
		$c->condition = 'loginid=:loginid';
		$c->params = array(':loginid' => $this->username);
		$user = User::model()->find($c);

		if($user == null){
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}else
			if ($user->password !== $user->hashPassword($this->password)){
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}else
			{
				$this->_id = $user->id;
				$this->_role = $user->is_super;
				$this->username = $user->name;
				$this->errorCode = self::ERROR_NONE;
			}

		return !$this->errorCode;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getRole()
	{
		return $this->_role;
	}
}