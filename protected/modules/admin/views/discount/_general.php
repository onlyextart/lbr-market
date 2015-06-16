<div class="row">      
    <?php
        echo $form_view->error($model, 'name');
        echo $form_view->labelEx($model, 'name');
        echo $form_view->textField($model, 'name');
    ?>
</div>

<div class="row">      
    <?php
        echo $form_view->labelEx($model, 'sum');
        echo $form_view->textArea($model, 'sum');
    ?>
</div>

<div class="row">      
    <?php
        echo $form_view->error($model, 'start_date');
        echo $form_view->labelEx($model, 'start_date');
        echo $form_view->textField($model, 'start_date');
    ?>
</div>

<div class="row">      
    <?php
        echo $form_view->error($model, 'end_date');
        echo $form_view->labelEx($model, 'end_date');
        echo $form_view->textField($model, 'end_date');
    ?>
</div>

<div class="row">      
    <?php
        echo $form_view->labelEx($model, 'published');
        echo $form_view->dropDownList($model, 'published',array('0'=>'Нет','1'=>'Да'));
    ?>
</div>

