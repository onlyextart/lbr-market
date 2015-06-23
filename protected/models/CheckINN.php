<?php
    class CheckINN extends CValidator{
        
        // Сообщения об ошибках
        public $notValid = 'Некорректный ИНН';
        
        // этот метод вызвается непосредственно при валидации
        protected function validateAttribute($object, $attribute) {
            $country=$object->country_id;
            if ($country==UserCountry::RUSSIA){
            $inn = $object->$attribute;
            if (!empty($inn)&&!preg_match('/^[0-9]+$/', $inn)){
                $this->addError ($object, $attribute, $this->notValid);
            }
            else if(!empty($inn)){
                $inn_array=str_split($inn);
                if (count($inn_array)==10){
                    //step 1
                    $factors_array=array(2,4,10,3,5,9,4,6,8);
                    $multipl_array=array();
                    for($i=0;$i<9;$i++){
                        $multipl_array[$i]=$inn_array[$i]*$factors_array[$i];
                    }
                    $sum=0;
                    for($i=0;$i<9;$i++){
                        $sum+=$multipl_array[$i];
                    }
                    //step 2
                    $partity=$sum%11;
                    //step 3
                    if ($partity>9){
                        $partity=$partity%10;
                    }
                    //step 4
                    if($partity!==(int)$inn_array[9]){
                        $this->addError ($object, $attribute, $this->notValid); 
                    }
                
                }
                else if(count($inn_array)==12){
                    //step 1
                    $factors_array=array(7,2,4,10,3,5,9,4,6,8,0);
                    $multipl_array=array();
                    for($i=0;$i<11;$i++){
                        $multipl_array[$i]=$inn_array[$i]*$factors_array[$i];
                    }
                    $sum=0;
                    for($i=0;$i<11;$i++){
                        $sum+=$multipl_array[$i];
                    }
                    //step 2
                    $partity1=$sum%11;
                    //step 3
                    if ($partity1>9){
                        $partity1=$partity1%10;
                    }
                    //step 4
                    $factors_array2=array(3,7,2,4,10,3,5,9,4,6,8,0);
                    $multipl_array2=array();
                    for($i=0;$i<12;$i++){
                        $multipl_array2[$i]=$inn_array[$i]*$factors_array2[$i];
                    }
                    $sum2=0;
                    for($i=0;$i<11;$i++){
                        $sum2+=$multipl_array2[$i];
                    }
                    //step 5
                    $partity2=$sum2%11;
                    //step 6
                    if ($partity2>9){
                        $partity2=$partity2%10;
                    }
                    //step 7
                    if ($partity1!==(int)$inn_array[10]||$partity2!==(int)$inn_array[11]){
                    $this->addError ($object, $attribute, $this->notValid);  
                    }
                }
                else{
                    $this->addError ($object, $attribute, $this->notValid);
                }
            }
            }
        }
    }

