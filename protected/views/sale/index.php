<div class="breadcrumbs">
    <?php
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => Yii::app()->params['breadcrumbs'],
            'activeLinkTemplate' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="{url}">{label}</a></span>',
            'inactiveLinkTemplate' => '{label}',
            'homeLink' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="/">Главная</a></span>',
            'tagName' => 'span',
            'htmlOptions' => array(
                'xmlns:v' => 'http://rdf.data-vocabulary.org/#',
            ),
        ));
    ?>
</div>
<div class="bestoffer-wrapper">
    <div class="elements">
        <h1>Распродажа</h1><img class="spec-label" src="/images/sale-label.png">
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
