<?php

class PhalanxContentModel extends PhalanxModel {
	protected $title = null;
	const SPAM_WHITELIST_TITLE = 'Spam-whitelist';
	const SPAM_WHITELIST_NS_TITLE = 'Mediawiki:Spam-whitelist';

	public function __construct( $title, $lang = null, $id = 0 ) {
		parent::__construct( __CLASS__, array( 'title' => $title, 'lang' => $lang, 'id' => $id ) );
	}
	
	public function isOk() { 
		return ( 
			$this->wg->User->isAllowed( 'phalanxexempt' ) || 
			!( $this->title instanceof Title ) || 
			( $this->title->getPrefixedText() == self::SPAM_WHITELIST_NS_TITLE )  
		);
	}

	public function getText() {
		return preg_replace( '/\s+/', ' ', preg_replace( '/[^\PP]+/', '', ( !is_null( $this->text ) ) ? $this->text : $this->title->getFullText() ) );
	}

	public function buildWhiteList() {
		wfProfileIn( __METHOD__ );

		$whitelist = array();
		$content = $this->wf->msgForContent( self::SPAM_WHITELIST_TITLE );
		
		if ( $this->wf->emptyMsg( self::SPAM_WHITELIST_TITLE, $content ) ) {
			wfProfileOut( __METHOD__ );
			return $whitelist;
		}
			
		$content = array_filter( array_map( 'trim', preg_replace( '/#.*$/', '', explode( "\n", $content ) ) ) );
		if ( !empty( $content ) ) {
			foreach ( $content as $regex ) {
				$regex = str_replace( '/', '\/', preg_replace('|\\\*/|', '/', $regex) );
				$regex = "/https?:\/\/+[a-z0-9_.-]*$regex/i";
				$this->wf->suppressWarnings();
				$regexValid = preg_match($regex, '');
				$this->wf->restoreWarnings();
				if ( $regexValid === false ) continue;
				$whitelist[] = $regex;
			}
		}

		Wikia::log( __METHOD__, __LINE__, count( $whitelist ) . ' whitelist entries loaded.' );

		wfProfileOut( __METHOD__ );
		return $whitelist;
	}
	
	public function displayBlock() {
		$this->wg->Out->setPageTitle( $this->wf->msg( 'spamprotectiontitle' ) );
		$this->wg->Out->setRobotPolicy( 'noindex,nofollow' );
		$this->wg->Out->setArticleRelated( false );
		$this->wg->Out->addHTML( Html::openElement( 'div', array( 'id' => 'spamprotected_summary' ) ) );
		$this->wg->Out->addWikiMsg( 'spamprotectiontext' );
		$this->wg->Out->addHTML( Html::element( 'p', array(), $this->wf->Msg( 'phalanx-stats-table-id' ) . " #{$this->block->id}" ) );
		$this->wg->Out->addWikiMsg( 'spamprotectionmatch', "<nowiki>{$this->block->text}</nowiki>" );
		$this->wg->Out->addWikiMsg( 'phalanx-content-spam-summary' );
		$this->wg->Out->returnToMain( false, $this->title );
		$this->wg->Out->addHTML( Html::closeElement( 'div' ) );
		$this->logBlock();
	}
	
	public function contentBlock() {
		$msg = $this->wf->msgExt( 'spamprotectionmatch', 'parseinline', "<nowiki>{$this->block->text} (Block #{$this->block->id})</nowiki>" ); 
		$this->logBlock();
		return $msg;
	}
	
	public function reasonBlock() {
		$msg = $this->wf->msgExt( 'phalanx-title-move-summary', 'parseinline' );
		$msg .= Html::element( 'p', array(), $this->wf->Msg( 'phalanx-stats-table-id' ) . " #{$this->block->id}" );
		$msg .= $this->wf->msgExt( 'spamprotectionmatch', 'parseinline', "<nowiki>{$this->block->text}</nowiki>" );
		$this->logBlock();
		
		return $msg;
	}
	
	public function textBlock() {
		$this->logBlock();
		return $this->block->text;
	}
	
	public function match_summary( $summary ) {
		return $this->setText( $summary )->match( "summary" );
	} 
	
	public function match_summary_old() {
		/* problem with Phalanx service? - use previous version of Phalanx extension - tested */
		return ContentBlock::onEditFilter( '', $this->getText(), $this->block );
	}
	
	public function match_content( $textbox ) {
		return $this->setText( $textbox )->match( "content" ); 
	}
	
	public function match_content_old() {
		/* problem with Phalanx service? - use previous version of Phalanx extension - tested */
		return ContentBlock::onEditFilter( $this->getText(), '', $this->block );
	}
	
	public function match_title() {
		return $this->match( "title" );
	}
	
	public function match_title_old() {
		/* problem with Phalanx service? - use previous version of Phalanx extension - tested */
		return TitleBlock::genericTitleCheck( $this->title, $this->block );
	}

	public function match_question_title() {
		return $this->match( "question_title" );
	}
	
	public function match_question_title_old() {
		/* problem with Phalanx service? - use previous version of Phalanx extension - tested */
		return QuestionTitleBlock::badWordsTest( $this->title, $this->block );
	}
}
