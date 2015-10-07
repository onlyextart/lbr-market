<?php  
$mess = Yii::app()->user->getFlash('message');
$error = Yii::app()->user->getFlash('error');
?>
<script>
     alertify.set({ delay: 6000 });
        <?php if ($mess) :?>
            alertify.success('<?php echo $mess; ?>');
        <?php endif; ?>
        <?php if ($error) :?>
            alertify.error('<?php echo $error; ?>');
        <?php endif; ?>
</script>

<?php    
    if(!empty($bestoffer)) {
        echo $bestoffer;
    }
?>
<?php 
    if(!empty($hitProducts)) {
        echo $hitProducts;
    }
?>
<div class="clearfix"></div>
<?php
    if(!empty($makers)) {
        echo $makers;
    }
?>
<div class="clearfix"></div>