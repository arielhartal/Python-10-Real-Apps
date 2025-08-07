<?php

	$levelHash = $_REQUEST['id'];	//	d75cde811b13d9fb5cd5a18fea58e204
	$type = $_REQUEST['type'];		//	ChartFFR_music
	
	echo file_get_contents('level/' . $levelHash . '.swf');