<?php
	/**
	* Maintenance script to get number of RelatedVideos (NS 1100) articles on the wiki with wgRelatedVideosPartialRelease = false
	* This is one time use script
	* @author Saipetch Kongkatong
	*/

	/**
	 * get number of RelatedVideos articles on the wiki
	 * @param string $dbname
	 */
	function getTotalRV( $dbname ) {
		try {
			$db = wfGetDB( DB_SLAVE, array(), $dbname );
		} catch (Exception $e) {
			echo "Could not connect to database: ".$e->getMessage()."\n";
			return;
		}

		$row = $db->selectRow(
			array( 'page' ),
			array( 'count(*) cnt' ),
			array(
				'page_namespace' => 1100
			),
			__METHOD__
		);

		$cnt = ( $row ) ? $row->cnt : 0 ;
		echo "\tTotal RelatedVideos articles (NS1100): $cnt\n";

		return $cnt;
	}

	/**
	 * Remove RelatedVideos articles on the wiki
	 * @param string $dbname
	 */
	function removeRVArticles( $dryRun, $dbname, $wikiId ) {
		try {
			$db = wfGetDB( DB_MASTER, array(), $dbname );
		} catch (Exception $e) {
			echo "Could not connect to database: ".$e->getMessage()."\n";
			return;
		}

		$res = $db->select(
			array( 'page' ),
			array( 'page_id, page_title' ),
			array(
				'page_namespace' => 1100
			),
			__METHOD__
		);
		
		while ( $res && $row = $db->fetchRow( $res ) ) {
			echo "\tRemoving page 'RelatedVideos:".$row['page_title']."' (id: ".$row['page_id'].")\n";

			nukePage($dryRun, $dbname, $row['page_id']);
		}

		$db->freeResult($res);
	}

	function nukePage ( $dryRun, $dbname, $id ) {
		try {
			$dbw = wfGetDB( DB_MASTER, array(), $dbname );
		} catch (Exception $e) {
			echo "Could not connect to database: ".$e->getMessage()."\n";
			return;
		}

		$tbl_pag = $dbw->tableName( 'page' );
		$tbl_rec = $dbw->tableName( 'recentchanges' );
		$tbl_rev = $dbw->tableName( 'revision' );

		# Get corresponding revisions
		echo "\tSearching for revisions...";
		$res = $dbw->query( "SELECT rev_id FROM $tbl_rev WHERE rev_page = $id" );
		$revs = array();
		foreach ( $res as $row ) {
			$revs[] = $row->rev_id;
		}
		$count = count( $revs );
		echo "found $count.\n";

		# Delete the page record and associated recent changes entries
		if ( !$dryRun ) {
			echo "\tDeleting page record...";
			$dbw->query( "DELETE FROM $tbl_pag WHERE page_id = $id" );
			echo "done.\n";
			echo "\tCleaning up recent changes...";
			$dbw->query( "DELETE FROM $tbl_rec WHERE rc_cur_id = $id" );
			echo "done.\n";

			if ( $count ) {
				echo "\tDeleting revisions...";
				$tbl_rev = $dbw->tableName( 'revision' );
				$set = implode( ', ', $revs );
				$dbw->query( "DELETE FROM $tbl_rev WHERE rev_id IN ( $set )" );
				echo "done.\n";
			}
		}
	}

	/**
	 * set up video info
	 * @global boolean $dryRun
	 * @global boolean $quiet
	 * @param integer $wikiId 
	 */
	function setupVideoInfo( $wikiId ) {
		global $dryRun, $quiet;

		$file = dirname( __FILE__ ).'/../../extensions/wikia/VideoHandlers/videoInfo/maintenance.php';
		$cmd = "SERVER_ID={$wikiId} php {$file} --conf=/usr/wikia/docroot/wiki.factory/LocalSettings.php";
		if ( $dryRun ) {
			$cmd .= " --dry-run";
		}
		if ( $quiet ) {
			$cmd .= " --quiet";
		}
		echo "\tCommand: $cmd\n";
		$result = wfShellExec( $cmd, $retval );
		if ( $retval ) {
			echo "Error code $retval: $result \n";
		} else {
			echo "$result \n";
		}
	}

	/**
	 * enable Special Video Ext
	 * @param integer $wikiId 
	 */
	function enableSpecialVideosExt( $wikiId ) {
		echo "Enable Special Videos Ext:\n";
		$feature = 'wgEnableSpecialVideosExt';
		$wgValue = WikiFactory::getVarByName( $feature, $wikiId );
		if ( empty($wgValue) ) {
			echo "\tError invalid params. \n";
		} else {
			WikiFactory::setVarByName( $feature, $wikiId, true, "enable Special Videos Ext for wikis that enable Related Videos" );
			WikiFactory::clearCache( $wikiId );
			echo "\tUpdate $feature from ".var_export( unserialize($wgValue->cv_value), true )." to true. \n";
		}
	}

	/**
	 * copy videos from RelatedVideos articles to GlobalList
	 * @param integer $wikiId
	 */
	function copyRVtoGlobalList( $wikiId ) {
		global $dryRun, $quiet;

		$file = dirname( __FILE__ ).'/copyVideosFromRVToGlobalList.php';
		$cmd = "SERVER_ID={$wikiId} php {$file} --conf=/usr/wikia/docroot/wiki.factory/LocalSettings.php";
		if ( $dryRun ) {
			$cmd .= " --dry-run";
		}
		if ( $quiet ) {
			$cmd .= " --quiet";
		}
		echo "\tCommand: $cmd\n";
		$result = wfShellExec( $cmd, $retval );
		if ( $retval ) {
			echo "Error code $retval: $result \n";
		} else {
			echo "$result \n";
		}
	}

	// ----------------------------- Main ------------------------------------

	ini_set( "include_path", dirname( __FILE__ )."/../" );
	ini_set('display_errors', 1);

	require_once( "commandLine.inc" );

	if ( isset($options['help']) ) {
		die( "Usage: php maintenance.php [--help] [--getTotalRV] [--setupVideoInfo] [--enableSpecialVideosExt] [--copyRVtoGlobalList]
		--getTotalRV                   get total number of RelatedVideo articles
		--setupVideoInfo               set up video info table
		--enableSpecialVideosExt       enable Special Video Ext
		--copyRVtoGlobalList           copy videos from RelatedVideo articles to GlabalList
		--removeRVPages                remove the RelatedVideo namespace pages
		--dry-run                      dry run (for setupVideoInfo, copyRVtoGlobalList)
		--quiet                        show summary result only (for setupVideoInfo, copyRVtoGlobalList)
		--help                         you are reading it right now\n\n" );
	}

	$getTotalRV = ( isset($options['getTotalRV']) );
	$setupVideoInfo = ( isset($options['setupVideoInfo']) );
	$enableSpecialVideosExt = ( isset($options['enableSpecialVideosExt']) );
	$dryRun = ( isset($options['dry-run']) );
	$quiet = ( isset($options['quiet']) );
	$copyRVtoGlobalList = ( isset($options['copyRVtoGlobalList']) );
	$removeRVPages = ( isset($options['removeRVPages']) );

	if ( empty($wgCityId) ) {
		die( "Error: Invalid wiki id." );
	}

	echo "Base wiki: ".$wgCityId."\n";

	// get var id
	$var = WikiFactory::getVarByName( 'wgRelatedVideosPartialRelease', $wgCityId );
	echo "wgRelatedVideosPartialRelease ID: ".$var->cv_id."\n";

	// get list of wikis with wgRelatedVideosPartialRelease = false
	$wikis = WikiFactory::getListOfWikisWithVar( $var->cv_id, 'bool', '=' , false, true );
	$total = count( $wikis );
	echo "Total wikis (wgRelatedVideosPartialRelease = false): ".$total."\n";

	$counter = 0;
	$failed = 0;

	foreach( $wikis as $wikiId => $detail ) {
		$counter++;
		echo "[$counter of $total] Wiki $wikiId ";
		$wiki = WikiFactory::getWikiById( $wikiId );
		if ( !empty($wiki) && $wiki->city_public == 1 ) {
			$dbname = $wiki->city_dbname;

			echo "($dbname): \n";

			// get number of RelatedVideos articles on the wiki
			if ( $getTotalRV ) {
				getTotalRV( $dbname );
			}

			// Remove the RelatedVideos articles
			if ( $removeRVPages ) {
				if (getTotalRV( $dbname )) {
					removeRVArticles( $dryRun, $dbname, $wikiId );
				}
			}

			// set up video info
			if ( $setupVideoInfo ) {
				setupVideoInfo( $wikiId );
			}

			// enable Special Video Ext
			if ( $enableSpecialVideosExt ) {
				enableSpecialVideosExt( $wikiId );
			}

			// copy videos from RelatedVideos articles to GlobalList
			if ( $copyRVtoGlobalList ) {
				copyRVtoGlobalList( $wikiId );
			}
		} else {
			echo "......... NOT FOUND or CLOSED\n";
			$failed++;
		}
	}

	echo "Total Wikis: ".$counter.", Success: ".($counter-$failed).", Failed: $failed\n\n";