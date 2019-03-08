<?php
	require_once("../ariadne.inc");
	require_once($ariadne . "/bootstrap.php");

	require_once( AriadneBasePath . "/configs/axstore.phtml");
	require_once( AriadneBasePath . "/configs/cache.phtml");
	require_once( AriadneBasePath . "/stores/".$ax_config["dbms"]."store.phtml");
	require_once( AriadneBasePath . "/stores/".$store_config["dbms"]."store_install.phtml");

	$ERRMODE="text";

	$inst_store = $store_config["dbms"]."store_install";
	$store=new $inst_store(".",$store_config);

	echo "== creating main Ariadne Object Store\n\n";

	if ($store->initialize()) {
		require_once("init_database_data.php");

		foreach ($properties as $name => $property) {
			$store->create_property($name, $property);
		}

		$store->add_type("pobject","pobject");
		$store->add_type("pdir","pobject");
		$store->add_type("pdir","ppage");
		$store->add_type("pdir","pdir");
		$store->add_type("pshortcut","pobject");
		$store->add_type("pshortcut","pshortcut");
		$store->add_type("puser","pobject");
		$store->add_type("puser","ppage");
		$store->add_type("puser","puser");
		$store->add_type("pgroup","pobject");
		$store->add_type("pgroup","ppage");
		$store->add_type("pgroup","pdir");
		$store->add_type("pgroup","puser");
		$store->add_type("pgroup","pgroup");
		$store->add_type("ppage","pobject");
		$store->add_type("ppage","ppage");

		// install psite types and properties

		$store->add_type("psite","pobject");
		$store->add_type("psite","ppage");
		$store->add_type("psite","pdir");
		$store->add_type("psite","psection");
		$store->add_type("psite","psite");

		// install pfile type

		$store->add_type("pfile","pobject");
		$store->add_type("pfile","pfile");

		// install pphoto(book) types

		$store->add_type("pphoto","pobject");
		$store->add_type("pphoto","pfile");
		$store->add_type("pphoto","pphoto");
		$store->add_type("pphotobook","pobject");
		$store->add_type("pphotobook","ppage");
		$store->add_type("pphotobook","pdir");
		$store->add_type("pphotobook","pphoto");
		$store->add_type("pphotobook","pphotobook");

		// install pprofile type

		$store->add_type("pprofile","pobject");
		$store->add_type("pprofile","ppage");
		$store->add_type("pprofile","pdir");
		$store->add_type("pprofile","pprofile");

		// install psection type

		$store->add_type("psection", "pobject");
		$store->add_type("psection", "ppage");
		$store->add_type("psection", "pdir");
		$store->add_type("psection", "psection");

		// install pproject type

		$store->add_type("pproject", "pobject");
		$store->add_type("pproject", "ppage");
		$store->add_type("pproject", "pdir");
		$store->add_type("pproject", "psection");
		$store->add_type("pproject", "pproject");

		// install punittest type
		$store->add_type("punittest", "pobject");
		$store->add_type("punittest", "ppage");
		$store->add_type("punittest", "pdir");
		$store->add_type("punittest", "punittest");

		if ($error) {
			error($error);
		}

	} else {
		error("store not initialized.");
	}
	$store->close();

	// session store

	$inst_store = $session_config["dbms"]."store_install";
	$sessionstore=new $inst_store(".",$session_config);

	echo "== creating Ariadne Session Store\n\n";
	if ($sessionstore->initialize()) {
		$sessionstore->add_type("psession","pobject");
		$sessionstore->add_type("psession","psession");
		$sessionstore->save( '/', 'pobject', new baseObject );
	} else {
		error("store not initialized.");
	}
	$sessionstore->close();

	// cache store

	$inst_store = $cache_config["dbms"]."store_install";
	$cachestore=new $inst_store(".",$cache_config);

	echo "== creating Ariadne Session Store\n\n";
	if ($cachestore->initialize()) {
		foreach ($cacheproperties as $name => $property) {
			$cachestore->create_property($name, $property);
		}
		$cachestore->add_type("pcache","pobject");
		$cachestore->add_type("pcache","pcache");

		$cachestore->save( '/', 'pobject', new baseObject );
	} else {
		error("store not initialized.");
	}
	$cachestore->close();

?>
