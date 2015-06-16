<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;

    public function authenticate()
    {
        $this->setState('admin', false);
        $status = 1;
        $record = User::model()->findByAttributes(array('login'=>$this->username));
        if(!$record) {
            $status = 0;        
            $record = AuthUser::model()->findByAttributes(array('login'=>$this->username));
        }
        ////else if($record->type_contact) {
            //$status = 2;
        //}
        
        $this->errorCode = $this->getError($record);
        
        if($this->errorCode==self::ERROR_NONE) {
            if($status == '0') {
                $this->setState('level', AuthGroup::model()->findByPk($record->g_id)->level);
                $this->setState('admin', true);
                $this->_id = $record->g_id;
                //$this->setState('_id', $record->g_id);
            } else {
                $this->_id = $record->id;
                //$this->setState('_id', $record->id);
                $this->setState('level', 1000);
            }
            //var_dump($this->_id);exit;
            $this->setState('_id', $record->id);
            $this->setState('shop', $status);
            // for autentification in exchange.lbr.ru
            $this->setState('transport', 1000000);
        }

        return $this->errorCode;
    }

    protected function getError($user=null)
    {
        if($user===null)
            return self::ERROR_USERNAME_INVALID;
        elseif(in_array($user->status, array(User::USER_TEMPORARY_BLOCKED, User::USER_BLOCKED, User::USER_NOT_CONFIRMED, User::USER_NOT_ACTIVATED)))
            return 1000 + $user->status;
        elseif($user->password!==crypt($this->password,$user->password))
            return self::ERROR_PASSWORD_INVALID;
        else
            return self::ERROR_NONE;
    }
    
    public function getId()
    {
        return $this->_id;
    }
}