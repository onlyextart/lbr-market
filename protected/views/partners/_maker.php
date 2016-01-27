<?php
        $path = Yii::getPathOfAlias('webroot');
            if (file_exists($path . $maker->logo)){
                echo CHtml::openTag('div', array('class' => 'maker_wrapper'));
                    echo CHtml::openTag('div', array('class' => 'maker_inner_wrapper'));
                        echo CHtml::openTag('a', array('href' => $link,'target'=>'_blank'));
                            echo CHtml::image($maker->logo, $maker->name, array());
                        echo CHtml::closeTag('a');
                    echo CHtml::closeTag('div');
                echo CHtml::closeTag('div');  
            }