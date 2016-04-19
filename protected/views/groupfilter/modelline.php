<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    ));        
?>
<div class="modellines-wrapper">
    <?php if(!empty($title)): ?>
    <h1><?php echo $title ?></h1>
    <?php endif; ?>
    <div class="elements">
        <?php if (!empty($response_top)): ?>
            <?php echo $response_top; ?>
        <?php elseif (!empty($response_all)): ?>
            <?php echo $response_all; ?>
        <?php else: ?>
        <span class="empty">Информация не найдена.</span>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
     $(document).on('click','.link-brands', function() {
            $(".modellines-wrapper .elements").empty();
            $(".modellines-wrapper .elements").html('<?php echo (!empty($response_all))? $response_all:"<span class=\"empty\">Нет товаров.</span>"?>');
            $('.modelline').dcAccordion({
                eventType: 'click',
                autoClose: true,
                saveState: true,
                disableLink: true,
                speed: 'fast',
                showCount: false
            });
            $('.modellines-wrapper .dcjq-parent-li').each(function( index ) {
                $( this ).find('a').removeClass("active");
                $( this ).find('ul').hide();
            });
        });
//        $(document).on('click','.link-modellines', function() {
//           $('.modelline li.non_top').css('display','block');
//           $(this).css('display','none');
//        });
    });
</script>