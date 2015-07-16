<div class="total">
    <div class="full">
    <div class="form wide">
    <?php 
        $price=0;
        $form_view = $this->beginWidget('CActiveForm', array(
            'id'=>'order-form',
            'action'=>$action,
            'enableClientValidation' => true,        
            'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange' => true,
            'afterValidate'=>'js:function( form, data, hasError ) 
            {     
                if( hasError ){
                    return false;
                }
                else{
                    return true;
                }
             }'
            ),
        ));
    ?>
        
    <div class="left50">
        <div class="row">      
            <?php  
                echo $form_view->error($form, 'id'); 
                echo $form_view->labelEx($form, 'id');
                echo $form_view->textField($form, 'id', array('disabled'=>'true'));
            ?>
        </div>
        
        <div class="row">      
            <?php  
                echo $form_view->error($form, 'date_created'); 
                echo $form_view->labelEx($form, 'date_created');
                echo $form_view->textField($form, 'date_created', array('disabled'=>'true'));
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->error($form, 'status_id'); 
                echo $form_view->labelEx($form, 'status_id');
                echo $form_view->dropDownList($form, 'status_id', CHtml::listData(OrderStatus::model()->findAll(),'id','name'));
            ?>
        </div>

        <div class="row delivery-type">      
            <?php  
               echo $form_view->error($form, 'delivery_id'); 
               echo $form_view->labelEx($form, 'delivery_id');
               echo $form_view->dropDownList($form, 'delivery_id', CHtml::listData(Delivery::model()->findAll(),'id','name'),array('id'=>'delivery'));
            ?>
        </div>
        
        <div class="row">      
            <?php  
               echo $form_view->error($form, 'order_filial'); 
               echo $form_view->labelEx($form, 'order_filial');
               echo $form_view->textField($form, 'order_filial',array('disabled'=>'true'));
            ?>
        </div>
        
        <div class="row <?php echo ($form->delivery_id == 1) ? 'hide': ''?>">      
            <?php  
               echo $form_view->error($form, 'user_address');
               echo $form_view->labelEx($form, 'user_address');
               echo $form_view->textArea($form, 'user_address',array('id'=>'Order_user_address'));
            ?>
        </div>
        
        <div class="row">      
            <?php  
                echo $form_view->error($form, 'user_name'); 
                echo $form_view->labelEx($form, 'user_name');
                if (!empty($form->user_id)){
                  // echo $form_view->textField($form, 'user_name', array('disabled'=>'true'));
                    echo CHtml::link($form->user_name,array('user/edit','id'=>$form->user_id),array('target'=>'_blank'));
                }
                else{
                    echo $form_view->textField($form, 'user_name');   
                }
            ?>
        </div>


        <div class="row">      
            <?php
                echo $form_view->error($form, 'user_email');
                echo $form_view->labelEx($form, 'user_email');
                if (!empty($form->user_id)){
                   echo $form_view->textField($form, 'user_email', array('disabled'=>'true'));
                }
                else{
                    echo $form_view->textField($form, 'user_email');   
                }
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->error($form, 'user_phone');
                echo $form_view->labelEx($form, 'user_phone');
                if (!empty($form->user_id)){
                   echo $form_view->textField($form, 'user_phone', array('disabled'=>'true'));
                }
                else{
                    echo $form_view->textField($form, 'user_phone');   
                }
            ?>
        </div>
        
        <div class="row">      
            <?php
            if ($model->user->organization_type == User::LEGAL_PERSON) {
                echo $form_view->error($form, 'user_inn');
                ?>
                <label for="OrderForm_user_inn">
                    <span id="label_inn">
                        <?php echo empty($model->user->country_id) ? UserCountry::model()->getCountryLabel(UserCountry::RUSSIA) : UserCountry::model()->getCountryLabel($model->user->country_id); ?>  
                    </span>
                </label>
                <?php
                echo $form_view->textField($form, 'user_inn',array('disabled'=>'true'));
            }
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->error($form, 'user_comment'); 
                echo $form_view->labelEx($form, 'user_comment');
                echo $form_view->textArea($form, 'user_comment', array('disabled'=>'true'));
            ?>
        </div>

        <div class="row">      
            <?php  
                echo $form_view->error($form, 'admin_comment'); 
                echo $form_view->labelEx($form, 'admin_comment');
                echo $form_view->textArea($form, 'admin_comment');
            ?>
        </div>

    </div>
    <div class="right50">
        <h2>Товары</h2>
<!--        <a href='#'>Добавить товар</a>-->
        <?php
                $form_view_product=$this->beginWidget('CActiveForm', array(
                    'id'=>'order-product-form',
                    'action'=>$action,
                    'enableClientValidation' => true,        
                    'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange' => true,
                    'afterValidate'=>'js:function( form, data, hasError ) 
                    {     
                        if( hasError ){
                            return false;
                        }
                        else{
                            return true;
                        }
                    }'
                  ),
                ));
                
                ?>
              
               <table class="add_info">
                <tr>
                    <th>Название</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th>Каталожный номер</th>
                    <th>&nbsp;</th>
                </tr>
            
                <?php
                    if (!empty($form_product)) {
                        $price = 0;
                        foreach ($form_product as $num => $product_order) {
                            echo '<tr data-price>';
                            echo '<td>'.CHtml::link($product_order->name,$product_order->path,array('target'=>'_blank','class'=>'product_name')).'</td>';
                            echo '<td>'.CHtml::activeTextField($product_order, "[$num]count").'</td>';
                            
                            echo '<td>';
                                if($product_order->price){
                                    echo $product_order->total_price.' руб.';
                                    echo ((int)$product_order->currency > 1)?'<br>('.$product_order->count.' * '.$product_order->price.$product_order->currency_symbol.' * '.$product_order->currency.')':'';
                                } else echo Yii::app()->params['textNoPrice'];
                            echo '</td>';
                            echo '<td>'.$product_order->catalog_number.'</td>';
                            echo '<td><a class="delete" href="'.Yii::app()->createUrl("admin/orderProduct/delete", array("id"=>$product_order->id)).'" title="Удалить"><img src="/assets/6ecd7f96/gridview/delete.png" alt="Удалить"></a></td>';
                            echo '</tr>';
                            if(is_numeric($price) && !empty($product_order->price)) $price += $product_order->price*$product_order->currency*$product_order->count;
                            else $price = Yii::app()->params['textNoPrice'];
                        }
                    }
                  
             $this->endWidget(); 
             
            ?>
        </table>
        
        <div class="col_text">
            <h2>
                Итого к оплате:
            </h2>
         </div>
        <div class="col_value">
            <h2><?php echo (is_numeric($price))?$price.' руб.':Yii::app()->params['textNoPrice'];?></h2>
        </div>

        
          
    </div> 
        <?php $this->endWidget(); ?>  
    
   </div>
    
   
        

    

</div>
</div>

<script>
    $(function() {
        function changeRequired(label){
            if($('.delivery-type select[id=delivery]').val() !==<?php echo Delivery::DELIVERY_PICKUP?>) {
                if(!label.hasClass('required')) {
                    label.addClass('required');
                    label.append('<span class="required"> *</span>');
                }
            } else {
                label.removeClass('required');
                label.find('span').remove();
            }
        };
        
        $(document).ready(function(){
            var label_address=$('label[for=OrderForm_user_address]');
            changeRequired(label_address);
            
           $('.delivery-type select[id=delivery]').change(changeRequired(label_address));
        });
       
    });
</script>

