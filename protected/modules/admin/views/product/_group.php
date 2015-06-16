    <div class="row">      
            <?php  
                echo $form_view->labelEx($model, 'group');
                echo $form_view->textField($model, 'group', array('disabled'=>'true'));
                echo $form_view->hiddenField($model, 'product_group_id');
            ?>
     </div>
