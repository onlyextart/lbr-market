<?php if(!empty($model->id)): ?>
    <!--div class="row">      
        <?php  
            echo $form->labelEx($model, 'h1');
            echo $form->textField($model, 'h1');
        ?>
    </div-->

    <div class="row">      
        <?php  
            echo $form->labelEx($model, 'meta_title');
            echo $form->textField($model, 'meta_title');
        ?>
    </div>

    <div class="row">      
        <?php  
            echo $form->labelEx($model, 'meta_description');
            echo $form->textField($model, 'meta_description');
        ?>
    </div>

    <div class="row">      
        <?php  
            echo $form->labelEx($model, 'top_text');
            //echo $form->textField($model, 'top_text');
            $this->widget('application.components.SRichTextarea',array(
                'model'=>$model,
                'attribute'=>'top_text'));
            ?>
    </div>

    <div class="row">      
        <?php  
            echo $form->labelEx($model, 'bottom_text');
            //echo $form->textField($model, 'bottom_text');
            $this->widget('application.components.SRichTextarea',array(
                'model'=>$model,
                'attribute'=>'bottom_text'));
            ?>
    </div>
<?php endif; ?>