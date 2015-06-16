<?php

Yii::import('ext.elrtef.elRTE');

/**
 * Draw textarea widget
 */
class SRichTextarea extends elRTE
{
	public function setModel($model)
	{
		$this->model=$model;
	}
}
