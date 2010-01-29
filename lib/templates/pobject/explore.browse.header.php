<?php
	$ARCurrent->nolangcheck=true;
	if( $this->CheckLogin("read") && $this->CheckConfig() ) {
		include_once($this->store->get_config("code")."nls/ariadne.".$this->reqnls);
		
		if( !$arLanguage ) {
			$arLanguage = $nls;
		}
		
		$owner = $this->data->config->owner_name;
		if( !$owner ) {
			$owner = $this->data->owner_name;
		}
		$date = strftime("%m-%d-%Y",$this->data->ctime);
		$modified = strftime("%m-%d-%Y",$this->data->mtime);
		$mod_user = current($this->find("/system/users/", "login.value='".AddCSlashes($this->data->muser, ARESCAPE)."'", "system.get.name.phtml"));
		if( !$mod_user ) {
			$mod_user = $this->data->mod_user;
		}
		$name = $this->call("system.get.name.phtml");
		
		$this->call("typetree.ini");
		$icon = $this->call("system.get.icon.php");
		$iconalt = $this->type;
		if( $this->implements("pshortcut") ) {
			$overlay_icon = $icon;
			$overlay_alt = $this->type;
			$icon = current($this->get($this->data->path, "system.get.icon.php"));
			$iconalt = $this->vtype;
		}
?>
<img src="<?php echo $icon; ?>" alt="<?php echo htmlspecialchars($iconalt); ?>" title="<?php echo htmlspecialchars($iconalt); ?>" class="icon">
<?php
	if( $overlay_icon ) {
?>
<img src="<?php echo $overlay_icon; ?>" alt="<?php echo htmlspecialchars($overlay_alt); ?>" title="<?php echo htmlspecialchars($overlay_alt); ?>" class="overlay_icon">
<?php
	}
?>
<div class="sectionhead yuimenubar iconsection"><?php echo htmlspecialchars($name); ?></div>
<div id="browseheaderbody" class="sectionbody">
	<div id="browseheaderleft">
		<ul>
				<li><?php echo $ARnls["ariadne:created"]." ".htmlspecialchars($date).", ".strtolower($ARnls["ariadne:currentowner"])." ".htmlspecialchars($owner); ?></li>
				<li><?php echo $ARnls["ariadne:lastmodified"]." ".htmlspecialchars($modified)." ".strtolower($ARnls["ariadne:by"])." ".htmlspecialchars($mod_user); ?></li>
		</ul>
	</div>
	<div id="browseheaderright">
<?php
	if( !$this->data->name ) {
		foreach( $ARConfig->nls->list as $key => $value ) {
			if( $arLanguage == $key ) {
				$class = "selected";
			} else {
				$class = "unselected";
			}
			if( $this->data->{$key}->name ) {
				echo "<a class='$class' href='#' onClick=\"muze.ariadne.registry.set('store_root', muze.ariadne.registry.get('root') + '/-' + muze.ariadne.registry.get('SessionID') + '-/" . $key . "'); muze.ariadne.explore.objectadded(); return false;\" title=\"".htmlspecialchars($value)."\"><img class=\"flag\" src=\"".$AR->dir->images."nls/small/".$key.".gif\" alt=\"".htmlspecialchars($value)."\"></a> ";
			} else {
				echo "<a class='$class' href='#' onClick=\"muze.ariadne.registry.set('store_root', muze.ariadne.registry.get('root') + '/-' + muze.ariadne.registry.get('SessionID') + '-/" . $key . "'); muze.ariadne.explore.objectadded(); return false;\" title=\"".htmlspecialchars($value)."\"><img class=\"flag\" src=\"".$AR->dir->images."nls/small/faded/".$key.".gif\" alt=\"".htmlspecialchars($value)."\"></a> ";
			}
		}
	}
?>
	</div>
	<div class="clear">
	</div>
</div>
<?php
	}
?>