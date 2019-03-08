<?php

$ARCurrent->nolangcheck=true;
if (($this->CheckLogin("edit") || $this->CheckLogin("add", ARANYTYPE)) && $this->CheckConfig()) {
	$arLanguage=$this->getdata("arLanguage");
	if (!$arLanguage) {
		$arLanguage=$ARConfig->nls->default;
	}

	$this->call("system.save.tempfile.phtml");
	if ($this->error) {
		echo '<div class="error">' . $this->error . '</div>';
		echo '<script type="text/javascript">alert("' . AddCSlashes($this->error, ARESCAPE) . '"); </script>';
	}

	if (!$this->getdata("name", $arLanguage)) {
		if (($file=$this->getdata("file", $arLanguage)) && preg_match("|[^\/\\\]*\$|", $file, $matches)) {
			$arFilename = preg_replace("|[^a-z0-9\./_-]|i", "-", $matches[0]);
			$_POST[$arLanguage]["name"] = $arFilename;
		}
	}

	$arResult = $wgWizFlow;
}

?>