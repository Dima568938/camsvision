<?php

class UserIdentity extends CUserIdentity
{
	public $uid;
    public $fname;
    public $lname;
    public $photo;
    public $photoMedium;
    public $photoBig;
    public $sex;
    public $country;
    public $city;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */

    public function __construct($uid=1, $fname, $lname, $photo, $photoMedium, $photoBig, $sex, $country, $city)
	{
		$this->uid=$uid;
        $this->fname=$fname;
        $this->lname=$lname;
        $this->photo=$photo;
        $this->photoMedium=$photoMedium;
        $this->photoBig=$photoBig;
        $this->sex=$sex;
        $this->country=$country;
        $this->city=$city;
	}

	public function authenticate()
	{
		$user=User::model()->find('uid=?',array($this->uid));

		if($user===null){
		// создать нового пользователя
            $user = new User();
        }
        $user->uid = $this->uid;
        $user->first_name = $this->fname;
        $user->last_name = $this->lname;
        $user->photo = $this->photo;
        $user->photo_medium = $this->photoMedium;
        $user->photo_big = $this->photoBig;
        $user->country = $this->country;
        $user->city = $this->city;
        $user->sex = $this->sex;
        $user->last_visit=date('Y-m-d H:i:s',time());
        $user->save();
        $this->errorCode=self::ERROR_NONE;
		return $this->errorCode==self::ERROR_NONE;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->uid;
	}
}