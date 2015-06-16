<?php
$name = 'Ошибка';
$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin/'),
	$name
);
?>
<h1 class="error-message"><?php echo $error; ?></h1>

