<?php
class MyCCaptchaAction extends CCaptchaAction {
//    public $backColor = 0xFFFFFF;
    public $height = 40;
    protected function generateVerifyCode()
    {
        if($this->minLength<3)
            $this->minLength=3;
        if($this->maxLength>20)
            $this->maxLength=20;
        if($this->minLength>$this->maxLength)
            $this->maxLength=$this->minLength;
        $length=rand($this->minLength,$this->maxLength);

        // Тут указываем символы которые будут 
        // выводится у нас на капче. 
        $letters='1234567890';
        $code='';
        for($i=0;$i<$length;++$i)
        {
            $code.=$letters[rand(0, strlen($letters)-1)];
        }
        return $code;
    }
}