
    <div class="row">      
        <?php  
            echo $form->labelEx($model, 'name');
            echo $form->textField($model, 'name');
        ?>
    </div>

    <div class="row">      
        <?php  
            echo $form->labelEx($model, 'path');
            echo $form->textField($model, 'path');
        ?>
    </div>

    <div class="row">      
        <?php   
            echo $form->labelEx($model, 'published');
            echo $form->dropDownList($model, 'published', array(
                1=>'Да',
                0=>'Нет'
            ));
        ?>
    </div>

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
        ?>
    </div>

<?php

/*
return array(
    //'id'=>'Category',
    'showErrorSummary'=>true,
    'enctype'=>'multipart/form-data',
    'elements'=>array(
        'content'=>array(
            'type'=>'form',
            'title'=>'Общая информация',
            'elements'=>array(
                'name'=>array(
                    'type'=>'text',
                ),
                'published'=>array(
                    'type'=>'dropdownlist',
                    'items'=>array(
                        1=>'Да',
                        0=>'Нет'
                    ),
                ),
                'path'=>array(
                    'type'=>'text',
                ),
            ),
        ),
        'seo'=>array(
            'type'=>'form',
            'title'=>'Мета данные',
            'elements'=>array(
                'meta_title'=>array(
                    'type'=>'text',
                ),
                'meta_description'=>array(
                    'type'=>'text',
                ),
                'top_text'=>array(
                    'type'=>'SRichTextarea',
                ),
                'bottom_text'=>array(
                    'type'=>'SRichTextarea',
                ),
            ),
        ),
    ),
);*/

