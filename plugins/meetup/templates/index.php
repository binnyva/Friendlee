 
<form action="" method="post">
I'll be in <?php $html->buildInput('city_id', '', 'select', '', array('options'=>$sql->getById("SELECT id,name FROM City WHERE user_id=$_SESSION[user_id]"))); ?>.

<?php $html->buildInput("action",'','submit','Find Me People...'); ?>
</form>