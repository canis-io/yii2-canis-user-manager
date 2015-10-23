<?php
/**
 * @link http://canis.io
 *
 * @copyright Copyright (c) 2015 Canis
 * @license http://canis.io/license/
 */

namespace canis\userManager\models;

use Yii;
use yii\base\Model;

class EnableTwoFactorForm extends Model
{
    public $tfa;
    public $identity;
    public $verificationCode;
    public $secret;

    public function rules()
    {
        return [
            [['verificationCode'], 'required'],
            [['verificationCode'], 'validateVerificationCode']
        ];
    }

    public function validateVerificationCode()
    {
        if (!$this->tfa->verifyCode($this->secret, $this->verificationCode)) {
            $this->addError('verificationCode', 'Verification code was invalid. Please try again.');
        }
    }


    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function enable()
    {
        if ($this->validate()) {
            $this->identity->enableTwoFactor($this->secret);
            return true;
        } else {
            return false;
        }
    }
}
