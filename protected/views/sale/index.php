<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links' => Yii::app()->params['breadcrumbs'],
        'homeLink' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'/" itemprop="url"><span itemprop="title">Главная</span></a></div>',
        'activeLinkTemplate' => '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.Yii::app()->getBaseUrl(true).'{url}" itemprop="url"><span itemprop="title">{label}</span></a></div>',
        'inactiveLinkTemplate' => '{label}',
    ));        
?>
<div class="bestoffer-wrapper">
    <div class="elements">
        <h1>Распродажа</h1><img width="30" height="30" class="spec-label" src="/images/sale-label.png">
        <?php if(count($data->getData())): 
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $data,
                'cssFile'      => false,
                'itemView'     => '_view',
                'ajaxUpdate'   => false,
                'emptyText'    => 'На данный момент нет распродаж.',
                'itemsTagName' => 'div',
                'template'     => '{sorter}{items}{pager}',
                'htmlOptions'  => array('class'=>'best-spareparts-wrapper'),
                'sortableAttributes' => array('name'=>'Названию'),//, 'count'=>'Наличию'),
                'sorterHeader' => 'Сортировать по:',
                'pager'        => array(
                    'header'   => false,
                    'class' => 'LinkPager', 
                    'firstPageLabel' => 'В начало',
                    'prevPageLabel'  => 'Назад',
                    'nextPageLabel'  => 'Вперёд',
                    'lastPageLabel'  => 'В конец',
                    'cssFile'        => false
                )
            )); 
        ?>
        <?php else: ?>
              <div class="empty">На данный момент нет распродаж.</div>
        <?php endif; ?>
    </div>
</div>
