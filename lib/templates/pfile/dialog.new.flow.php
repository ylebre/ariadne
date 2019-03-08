<?php
	$ARCurrent->nolangcheck=true;
	// empty, do nothing for pobject.
	if ($this->CheckLogin("add") && $this->CheckConfig()) {
		$arLanguage=$this->getdata("arLanguage");
		if (!$arLanguage) {
			$arLanguage=$ARConfig->nls->default;
		}

		$arResult[0] = $wgWizFlow[0];
		$arResult[] = array(
			"title" => $ARnls["uploadfile"],
			"image" => $AR->dir->images."wizard/upload.png",
			"template" => "dialog.new.upload.php",
		);
		foreach( $wgWizFlow as $k => $flow ) {
			if( $k > 0 ) {
				$arResult[] = $flow;
			}
		}


		$arNewFilename = $this->getdata("arNewFilename","none");
		if (!$arNewFilename || !$this->getdata("name")) {
			if (($file=$this->getdata("file")) && preg_match("|[^\/\\\]*\$|", $file, $matches)) {
				$arFilename = trim(preg_replace("|[^a-z0-9\./_-]|i", "-", $matches[0]), '._-');
				if (!$arNewFilename) {
					$_POST['arNewFilename'] = $arFilename;
					$this->path = $this->store->make_path($this->parent, $arFilename);
				}
				if (!$this->getdata("name", $arLanguage)) {
					$_POST[$arLanguage]["name"] = $arFilename;
				}
			}
		}
	}
?>
