<div class="row">      
    <?php  
        echo $form_view->labelEx($model, 'h1');
        echo $form_view->textField($model, 'h1');
    ?>
</div>

<div class="row">      
    <?php  
        echo $form_view->labelEx($model, 'meta_title');
        echo $form_view->textField($model, 'meta_title');
    ?>
</div>

<div class="row">      
    <?php  
        echo $form_view->labelEx($model, 'meta_description');
        echo $form_view->textField($model, 'meta_description');
        
        /*$this->widget('application.components.SRichTextarea',array(
            'model'=>$model,
            'attribute'=>'meta_description'));
        */
    ?>
</div>

<div class="row">
    <?php 
        echo $form_view->labelEx($model, 'top_text');
        $this->widget('application.components.SRichTextarea',array(
            'model'=>$model,
            'attribute'=>'top_text'));
    ?>
</div>

