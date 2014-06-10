<h2>3 Things for <?php echo $nickname ?>...</h2>

<br /><br />
<form action="" method="post">

<textarea name="question" rows="3" cols="50" style="font-weight:bold;border:0;"><?php 
	echo i($data, 'question', 'What are the top three characteristics of your ideal boyfriend/girlfriend?'); ?></textarea>
<ul>
<li><textarea name="answer[]" rows="3" cols="50"><?php echo i($data, 'answer_1'); ?></textarea></li>
<li><textarea name="answer[]" rows="3" cols="50"><?php echo i($data, 'answer_2'); ?></textarea></li>
<li><textarea name="answer[]" rows="3" cols="50"><?php echo i($data, 'answer_3'); ?></textarea></li>
</ul>

<input type="hidden" name="things_id" value="<?php echo i($data,'id',0) ?>" />
<input type="hidden" name="person_id" value="<?php echo $QUERY['person_id'] ?>" />
<input type="submit" name="action" value="Save" class="btn btn-primary" />
</form>
