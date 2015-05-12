<?php
function multi_meet_people_box() {
	global $all_people;
?>
<div id="multi-meet-area-holder" class="panel panel-primary multi-meet-holder popup-holder">
<button type="button" class="close" id="multi-meet-closer">&times;</button>
<div class="panel-heading" id="multi-meet-title">Multi Meet</div>

<div id="multi-meet-area" class="panel-body">
<form action="" method="post" id="multi-meet-form">
<input type="text" name="multi-meet-filter" id="multi-meet-filter" target-field="people-list" class="filter-list" /><br />
<ul id="people-list">
<?php foreach($all_people as $id => $name) { ?>
<li><input type="checkbox" id="multi-meet-person-<?php echo $id ?>" value="<?php echo addslashes($name) ?>" class="people-filter" /> <label for="multi-meet-person-<?php echo $id ?>"><?php echo $name ?></label></li>
<?php } ?>
</ul>
<input type="submit" value="Insert" name="action" />
</form>

</div>
</div>
<?php
}
//$this->addHook("display_content_top", "multi_meet_people_box");

function multi_meet_insertLink() {
	print '<a href="#" id="multi-meet">Multi Meet</a>';
}
//$this->addHook("main_box_met_show_under", "multi_meet_insertLink");

function multi_meet_showJsCode() {
	global $config;
	print '<script src="' . $config['site_url'] . 'plugins/multi_meet/script.js" type="text/javascript"></script>';
}
//$this->addHook("display_page_end", "multi_meet_showJsCode");

function multi_meet_showCssCode() {
	global $config;
	print '<link href="' . $config['site_url'] . 'plugins/multi_meet/style.css" rel="stylesheet" type="text/css" />';
}
//$this->addHook("display_page_head", "multi_meet_showCssCode");
