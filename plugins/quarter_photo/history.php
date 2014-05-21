<?php
require('../../common.php');

$sex = i($QUERY, 'sex','f');
$sent_photos = $sql->getAll("SELECT person_id, point_status, sent_on, P.nickname, P.point FROM Plugin_Quarter_Photo Q
			INNER JOIN Person P ON P.id=Q.person_id
			WHERE P.user_id=$_SESSION[user_id] AND P.sex='$sex' ORDER BY Q.person_id, Q.sent_on DESC");

render(joinPath($config['site_folder'],'plugins/quarter_photo/templates/history.php'), true, true);
