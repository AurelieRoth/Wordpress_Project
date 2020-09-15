<?php
/*
Plugin Name: Christmas Song Special
Description: Donne une ligne aléatoire de la célèbre chanson "Jingle Bells", pour donner plus de magie pendant la période des fêtes !
Author: DaaTeam
Version: 1.0
*/
$jingle_bells = "Dashing through the snow
In a one horse open sleigh
Over the fields we go
Laughing all the way
Bells on bobtail ring
Making spirits bright
What fun it is to laugh and sing
A sleighing song tonight!
Oh, jingle bells, jingle bells
Jingle all the way
Oh! what fun it is to ride
In a one horse open sleigh, hey
Jingle bells, jingle bells
Jingle all the way
Oh! what fun it is to ride
In a one horse open sleigh
Now the ground is white
Go it while you're young
Take the girls tonight
And sing this sleighing song
Just get a bobtailed bay
Two forty as his speed
Hitch him to an open sleigh
And crack! You'll take the lead
Jingle bells, jigle bells
Jingle all the way
Oh, what fun it is to ride
In one horse open sleigh, hey
Jingle bells, jingle bells
Jingle all the way
Oh, what fun it is to ride
In one horse open sleigh
Oh, what fun it is to ride
In one horse open sleigh...";
$jingle_lines = explode("\n", $jingle_bells);
$random_line = $jingle_lines[mt_rand(0, count($jingle_lines) - 1)];
function echo_jingle() {
	global $random_line;
	echo "<span style='justify-content: center; background-color: rgba(255, 255, 255, 0.7); color: rgb(255, 50, 50);'>".$random_line."</span>";
}
add_action("wp", "echo_jingle");