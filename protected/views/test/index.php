<?php
   //Yii::app()->clientScript->registerScriptFile('http://www.baikalsr.ru/api-calc/?ver=2.2&setAccount=BS-0000189',CClientScript::POS_END);
   Yii::app()->clientScript->registerScriptFile('http://www.baikalsr.ru/api-calc/?ver=2.2&setAccount=BS-0000189');
?>

<script type="text/javascript">
    $(function() {
       $("#myCalculator").bsCalculator();
    });
</script>

<div id="myCalculator"></div>