<?php
	if ($this->CheckLogin("layout") && $this->CheckConfig()) {
		$this->resetloopcheck();

		$fstore	= $this->store->get_filestore_svn("templates");
		$svn	= $fstore->connect($this->id, $username, $password);

		if ($svn['info']['Revision']) {
			echo "Removing Version Control: ".$this->path."\n";
			$status = $fstore->svn_unsvn($svn);
		}
		// print_r($status);
	}
?>