<?php
	if ($this->CheckSilent("add",ARANYTYPE) && !$hideAdd) { ?>
		<input type="submit" value="<?echo $ARnls['ariadne:new']; ?>" onclick="muze.ariadne.explore.arshow('dialog.add',muze.ariadne.explore.tree.loaderUrl + muze.ariadne.registry.get('path') + 'dialog.add.php'); return false;">
<?php } ?>