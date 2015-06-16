<div class="row">      
            <?php  
                echo $form_view->labelEx($model, 'group_name');
                echo $form_view->textField($model, 'group_name', array('disabled'=>'true','id'=>'Product_group'));
               
            ?>
</div>

<div class="row">      
            <?php  
                echo $form_view->labelEx($model, 'product_id');
                echo $form_view->dropDownList($model, 'product_id',Discount::model()->getProduct($model->group_id),array('id'=>'Product_name'));
                //echo $form_view->hiddenField($model, 'product_id');
            ?>
</div>

