<?PHP

	$id = str_replace("/", "", $_REQUEST["id"]);
	
	$zip = new ZipArchive;
	if ($zip->open("profile_directory.zip") === TRUE) {
		echo $zip->getFromName("profile/" . $id);
		$zip->close();
	}