<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => Yii::app()->params['breadcrumbs'],
    'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . Yii::app()->getBaseUrl(true) . '/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
    'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . Yii::app()->getBaseUrl(true) . '{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
    'inactiveLinkTemplate' => '{label}',
));
?>
<div class="modellines-wrapper">
    <?php if (!empty($topText)): ?>
        <div class="text"><?php echo $topText ?></div>
    <?php endif; ?>
    <?php if (!empty($title)): ?>
        <h1><?php echo $title ?></h1>
    <?php endif; ?>
    <div class="elements">
        <?php if (!empty($response_top)): ?>
            <?php echo $response_top; ?>
        <?php elseif (!empty($response)): ?>
            <?php echo $response; ?>
        <?php else: ?>
            <span class="empty">Нет товаров.</span>
        <?php endif; ?>
    </div>
    <?php if (!empty($bottomText)): ?>
        <div class="text">
            <div><?php echo $bottomText ?></div>
            <span class="bottom-more">Подробнее...</span>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
//        $(".modellines-wrapper .modelline").each(function(index){
//            if ($(this).children(".dcjq-parent-li").find("ul>li").is(".non_top")){
//                alert('true');                   
//            }
//        });             
        $(".link-brands").on('click', function() {
            $(".modellines-wrapper .elements").empty().append('<?php echo (!empty($response))? $response:"Нет товаров." ?>');
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
        $(".link-modellines").live('click', function() {
           $('.modelline li.non_top').css('display','block');
           $(this).css('display','none');
        });
    });
</script>
