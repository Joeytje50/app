<?php

/**
 * CookieBlock module
 *
 * Blocks user based on browser cookie, interacts with and depends on AccountCreationTracker
 *
 * @author Lucas Garczewski <tor@wikia-inc.com>
 * @author Piotr Molski <moli@wikia-inc.com>
 * @date 2013-01-23
 */

class PhalanxUserCookieBlock extends PhalanxUserBlock {
	function __construct() {
		parent::__construct();
		F::setInstance( __CLASS__, $this );
	}

	public function blockCheck( User $user ) {
		$this->wf->profileIn( __METHOD__ );

		if ( $user->isAnon() || !class_exists( 'AccountCreationTracker' ) ) {
			$this->wf->profileOut( __METHOD__ );
			return true;
		}

		$tracker = F::build( 'AccountCreationTracker' );
		$hashes = $tracker->getHashesByUser( $user );

		$ret = true;
		if ( !empty( $hashes ) ) {
			foreach ( $hashes as $hash ) {
				$result = PhalanxService::match( "cookie", $hash );
				if ( $result !== false ) {
					if ( is_numeric( $result ) ) {
						$phalanxModel = F::build('PhalanxModel', array( $user, $result ) );
						// set block data ...
						$phalanxModel->userBlock( 'exact' );
						// ... and assign to user
						$user = $phalanxModel->getUser();
						$ret = false;
					} 
				} else {
					// TO DO
					/* problem with Phalanx service? */
					// include_once( dirname(__FILE__) . '/../prev_hooks/UserBlock.class.php';
					// include_once( dirname(__FILE__) . '/../prev_hooks/UserCookieBlock.class.php';
					// $ret = UserCookieBlock::blockCheck( $user );		
				}

				if ( !$ret ) {
					break;
				}
			}
		}

		$this->wf->profileOut( __METHOD__ );
		return $ret;
	}
}