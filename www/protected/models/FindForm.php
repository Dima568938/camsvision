<?php


class FindForm extends CFormModel
{
    public $query;
    public $sex;
    public $country;
    public $city;
    public $category;


    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // name, email, subject and body are required
            array('query', 'required'),
            // email has to be a valid email address
            array('query, sex, country, city', 'safe'),
            // verifyCode needs to be entered correctly
            //array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'query'=>'Строка для поиска',
        );
    }
}