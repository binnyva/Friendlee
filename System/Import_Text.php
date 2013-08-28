<?php
require '../common.php';

$level_id=1;

$text = 'Aliya
	Angitha
	Lachu
	Anagha
	Sai Sham
	Reshma FC
	Reshma NUALS
	Athira Kumar
	Jr
	Roshny
	Maneesha
	Sachin
	Vishnu Maya
	Krishnan
	Surjith
	Arun Basil
	Allan
	Anil
	Kavya
	Sradha
	Nadheem
	Blesson
	Evans
	Nikitha
	Raichand
	Staj
	Arjun
	Sanjay
	Aishwarya
	Apoorva
	Shazia
	Saran
	Sharon
	Jithin
	Sruthi Sara
	Anupama
	Anjana
	Gowri
	Cherian
	Anju';

$lines = explode("\n", $text);
print '<pre>';
foreach($lines as $l) {
	$person = trim($l);
	$sql->insert('Person', array(
		'nickname'	=> $person, 
		'level_id'	=> $level_id,
		'user_id'	=> 1,
		'status'	=> 1,
		'sex'		=> 'f'
	));
	echo $person . "\n";
}

