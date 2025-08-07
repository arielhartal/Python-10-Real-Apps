<?PHP

	$id = $_REQUEST['LID'];
	
	$zip = new ZipArchive;
	if ($zip->open("levels_directory.zip") === TRUE) {
		echo $zip->getFromName("levels/" . $id . ".txt");
		$zip->close();
	}