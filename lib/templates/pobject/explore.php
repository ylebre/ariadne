<?php
	$ARCurrent->nolangcheck = true;
	if ($this->CheckLogin("read") && $this->CheckConfig()) {
  		include_once($this->store->get_config("code")."nls/ariadne.".$this->reqnls);
		require_once($this->store->get_config("code")."modules/mod_json.php");

		if( !function_exists("yui_menuitems") ) {
			function yui_menuitems($menuitems, $menuname, $menuid='') {
				$result = '';
				if (is_array($menuitems)) {
					$result .= '<div';
					if ($menuid) {
						$result .= ' id="' . $menuid . '"';
					}
					$result .=' class="' . $menuname . '"><div class="bd"><ul class="first-of-type">';
					foreach ($menuitems as $item) {
						$submenu = '';
						$link = '';
						$image = '';
						$itemname = '';
						$onclick = '';
						$imgalt = '';
						
						if (!$item['href']) {
							$item['href'] = "#";
						}
						if ($item['onclick']) {
							$onclick = ' onclick="' . $item['onclick'] . '"';
						}
						if ($item['iconalt']) {
							$imgalt = ' alt="' . $item['iconalt'] . '"';
						}
						if ($item['label']) {
							$itemname = $item['label'];
						}
						if ($item['icon']) {
							$image = '<img src="' . $item['icon'] . '" ' . $imgalt . '>'; 
						}
						if (is_array($item['submenu'])) {
							$submenu .= yui_menuitems($item['submenu'], "yuimenu");
						}
						$link = '<a class="' . $menuname . 'itemlabel" href="' . $item['href'] . '"' . $onclick . '>';
						
						$result .= '<li class="' . $menuname . 'item">';
						$result .= $link;
						$result .= $image;
						$result .= "&nbsp;";
						$result .= $itemname;
						$result .= "</a>";
						$result .= $submenu;
						$result .= "</li>";
					}
					$result .= "</ul></div></div>";
				}
				return $result;
			}
		}
		
		$menuitems = array(
			array(
				'label' => $ARnls['ariadne:logoff'],
				'iconalt' => $ARnls['ariadne:logoff'],
				'icon' => $AR->dir->images . 'icons/medium/logout.png',
				'href' => $loader . $path .'logoff.php'
			),
			array(
				'label' => $ARnls['ariadne:search'],
				'iconalt' => $ARnls['ariadne:search'],
				'icon' => $AR->dir->images . 'icons/small/search.png',
				'onclick' => "muze.ariadne.explore.toolbar.searchwindow(); return false;",
				'href' => $this->make_local_url(). 'dialog.search.php'
			),
			array(
				'label' => $ARnls['ariadne:folders'],
				'iconalt' => $ARnls['ariadne:folders'],
				'icon' => $AR->dir->images . 'icons/small/view_tree.png',
				'href' => "#",
				'onclick' => 'muze.ariadne.explore.tree.toggle(); return false;'
			),
			array(
				'label' => $ARnls['ariadne:preferences'],
				'iconalt' => $ARnls['ariadne:preferences'],
				'icon' => $AR->dir->images . 'icons/small/preferences.png',
				'onclick' => "muze.ariadne.explore.arshow('edit_preferences','" . $this->store->root.$AR->user->path . "dialog.preferences.php'); return false;",
				'href' => $this->make_local_url($AR->user->path) . "dialog.preferences.php"
			),
			array(
				'iconalt' => $ARnls['ariadne:iconview'],
				'icon' => $AR->dir->images . 'icons/small/view_icon.png',
				'onclick' => 'return false;',
				'submenu' => array(
					array(
						'href' => "javascript:muze.ariadne.explore.viewpane.setviewmode('list');",
						'label' => $ARnls['ariadne:small'],
					),
					array(
						'href' => "javascript:muze.ariadne.explore.viewpane.setviewmode('icons');",
						'label' => $ARnls['ariadne:large'],
					),
					array(
						'href' => "javascript:muze.ariadne.explore.viewpane.setviewmode('details');",
						'label' => $ARnls['ariadne:details'],
					)
				)
			),
			array(
				'iconalt' => $ARnls['ariadne:help'],
				'icon' => $AR->dir->images . 'icons/small/help.png',
				'onclick' => 'return false;',
				'submenu' => array(
					array(
						'href' => "#",
						'onclick' => "muze.ariadne.explore.arshow('help', 'http://www.ariadne-cms.org/docs/'); return false;",
						'label' => $ARnls['ariadne:help']
					),
					array(
						'href' => "#",
						'onclick' => "muze.ariadne.explore.arshow('help_about','help.about.php'); return false;" ,
						'label' => $ARnls['ariadne:about']
					)
				)
			),
			array(
				'iconalt' => $ARnls['ariadne:up'],
				'icon' => $AR->dir->images . 'icons/small/up.png',
				//'href' => "javascript:muze.ariadne.explore.view('" . $this->parent . "');"
				'href' => $this->make_local_url($this->parent) . "explore.html",
				'onclick' => "muze.ariadne.explore.toolbar.viewparent(); return false;"
			)
		);


		/* retrieve HTTP GET variables */
		global $AR;


		$base_object = $AR->user;
//		$base_object = $this;
//		$base_object = current($this->get('/', 'system.get.phtml'));

		// This set initializes the tree from the user object
		$path 	= $base_object->path;
		$name 	= $base_object->nlsdata->name;
		$icon 	= $base_object->call('system.get.icon.php', array('size' => 'medium'));

		$loader = $this->store->root;
		$wwwroot = $AR->dir->www;
		$interface = $data->interface;

		$yui_base = $wwwroot . "js/yui/";
	//	$yui_base = "http://yui.yahooapis.com/2.5.2/";
	
	
		$viewmodes = array( "list" => 1, "details" => 1, "icons" => 1);
		$viewmode = $_COOKIE["viewmode"];
		if( !$viewmode || !$viewmodes[$viewmode] ) {
			$viewmode = 'list';
		}	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Ariadne<?php echo ": " . $AR->user->data->name . ": " . $data->$nls->name; ?></title>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<!--link rel="stylesheet" type="text/css" href="<?php echo $yui_base;?>fonts/fonts-min.css"-->
<link rel="stylesheet" type="text/css" href="<?php echo $yui_base;?>button/assets/skins/sam/button.css">
<link rel="stylesheet" type="text/css" href="<?php echo $yui_base;?>menu/assets/skins/sam/menu.css">
<link rel="stylesheet" type="text/css" href="<?php echo $yui_base;?>treeview/assets/skins/sam/treeview.css">
<link rel="stylesheet" type="text/css" href="<?php echo $yui_base;?>container/assets/skins/sam/container.css">
<link rel="stylesheet" type="text/css" href="<?php echo $yui_base;?>datatable/assets/skins/sam/datatable.css">
<link rel="stylesheet" type="text/css" href="<?php echo $yui_base;?>treeview/assets/skins/sam/treeview.css">

<script type="text/javascript" src="<?php echo $yui_base;?>yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>element/element-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>button/button-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>yahoo/yahoo-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>event/event-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>connection/connection-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>container/container_core-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>menu/menu-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>datasource/datasource-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>datatable/datatable-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>treeview/treeview-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>slider/slider-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>animation/animation-min.js"></script>
<script type="text/javascript" src="<?php echo $yui_base;?>container/container-min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo $AR->dir->styles; ?>explore.css">
<!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="<?php echo $AR->dir->styles; ?>explore.ie6.css"><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo $AR->dir->styles; ?>login.css">


<script type="text/javascript" src="<?php echo $wwwroot; ?>js/muze.js"></script>
<script type="text/javascript" src="<?php echo $wwwroot; ?>js/muze/util/pngfix.js"></script>
<script type="text/javascript" src="<?php echo $wwwroot; ?>js/muze/util/splitpane.js"></script>
<script type="text/javascript" src="<?php echo $wwwroot; ?>js/muze/ariadne/registry.js"></script>
<script type="text/javascript" src="<?php echo $wwwroot; ?>js/muze/ariadne/cookie.js"></script>
<script type="text/javascript" src="<?php echo $wwwroot; ?>js/muze/ariadne/explore.js"></script>

<script type="text/javascript">
	// Backwards compatibility hooks - these should be removed in the end.
	View = muze.ariadne.explore.view;
	LoadingDone = muze.ariadne.explore.loadingDone;
	objectadded = muze.ariadne.explore.objectadded;
	arEdit = muze.ariadne.explore.arEdit;
	updateChildren = muze.ariadne.explore.viewpane.update;
	selectItem = muze.ariadne.explore.viewpane.onSelectItem;
	Set = muze.ariadne.registry.set;
	Get = muze.ariadne.registry.get;
</script>

<script type="text/javascript">
	YAHOO.util.Event.onDOMReady(muze.ariadne.explore.toolbar.init);
	YAHOO.util.Event.onDOMReady(muze.ariadne.explore.sidebar.getInvisiblesCookie);
	muze.ariadne.registry.set('browse_template','explore.browse.'); // intentionally ending in .
	muze.ariadne.registry.set('viewmode', '<?php echo $viewmode; ?>');

	//once the DOM has loaded, we can go ahead and set up our tree:
	YAHOO.util.Event.onDOMReady(muze.ariadne.explore.tree.init, muze.ariadne.explore.tree, true);
	YAHOO.util.Event.onDOMReady(muze.ariadne.explore.splitpane.init);
</script>

<script type="text/javascript">
	// Pass the settings for the tree to javascript.
	muze.ariadne.explore.tree.loaderUrl 	= '<?php echo addslashes($loader); ?>';
	muze.ariadne.explore.tree.basePath 	= '<?php echo addslashes($path); ?>';
	muze.ariadne.explore.tree.baseName	= '<?php echo addslashes($name); ?>';
	muze.ariadne.explore.tree.baseIcon	= '<?php echo addslashes($icon); ?>';

	muze.ariadne.registry.set('root', '<?php echo addslashes($AR->root); ?>');
	muze.ariadne.registry.set('store_root', '<?php echo addslashes($this->store->root); ?>');
	
	// setting session ID for unique naming of windows within one ariadne session.
	muze.ariadne.registry.set("SessionID","<?php echo addslashes($ARCurrent->session->id); ?>");

	muze.ariadne.registry.set("path", "<?php echo addslashes($this->path); ?>");
	muze.ariadne.nls = eval(<?php echo JSON::encode($ARnls); ?>);
<?php
	if( $AR->user->data->windowprefs["edit_object_layout"] ) {
		echo "muze.ariadne.registry.set('window_new_layout', 1);\n";
	}
	if( $AR->user->data->windowprefs["edit_object_grants"] ) {
		echo "muze.ariadne.registry.set('window_new_grants', 1);\n";
	}
?>
</script>
</head>

<body class="yui-skin-sam">
	<div id="explore_top">
		<div class="logo">
			<a href="http://www.ariadne-cms.org">
				<img src="<?php echo $wwwroot;?>images/tree/logo2.gif" alt="Ariadne Web Application Server">
				<span class="ariadne">Ariadne</span>
				<span class="ariadne_sub">Web Application Server</span>
			</a>
		</div>
		<?php echo yui_menuitems($menuitems, "yuimenubar", "explore_menubar"); ?>
		<div class="searchdiv">
			<form action="explore.html" onsubmit="muze.ariadne.explore.toolbar.searchsubmit(this.arPath.value); return false;">
				<div>
					<input size="30" id="searchpath" class="text" type="text" name="arPath" value="<?php echo $this->path; ?>">
					<input type="image" src="<?php echo $AR->dir->www; ?>images/icons/small/go.png" title="<?php echo htmlspecialchars($ARnls['ariadne:search']); ?>" id="searchbutton" name="searchsubmit" value="<?php echo $ARnls["ariadne:search"]; ?>">
				</div>
			</form>
		</div>
	</div>
	
	<div id="explore_tree">
		<div id="treeDiv"></div>
		<div id="splitpane_slider"></div>
		<div id="splitpane_thumb"></div>
	</div>

	<div id="explore_managediv" class="managediv">
		<div id="sidebar" class="sidebar">
		<?php 
			$this->call("explore.sidebar.php", $arCallArgs);
		?>
		</div>
		<div class="browse section" id="browseheader">
		<?php
			$this->call("explore.browse.header.php");
		?>
		</div>
		<div class="browse" id="archildren">
			<?php
				$this->call("explore.browse.".$viewmode.".php");
			?>
		</div>
	</div>
</body>
</html>
<?php
	}
?>
