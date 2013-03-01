<?php

use Wikia\Search\QueryService;

class WikiaSearchAjaxController extends WikiaController {

    const RESULTS_PER_PAGE = 25;

    public function getNextResults(){
        $this->wf->ProfileIn(__METHOD__);

        $this->response->setVal('status', true);

        $query = $this->request->getVal('query', $this->request->getVal('search'));
        $query = htmlentities( Sanitizer::StripAllTags ( $query ), ENT_COMPAT, 'UTF-8' );

        $page = $this->request->getVal('page', 1);
        $rank = $this->request->getVal('rank', 'default');
        $crossWikia = $this->request->getBool('crossWikia');
        $hub = false;

        $isCorporateWiki = !empty($this->wg->EnableWikiaHomePageExt);

        $isInterWiki = $crossWikia ? true : $isCorporateWiki;

        $cityId = $isInterWiki ? 0 : $this->wg->CityId;

        $params = array(
			'page'			=>	$page,
			'length'		=>	self::RESULTS_PER_PAGE,
			'cityId'		=>	$cityId,
			'groupResults'	=>	$isInterWiki,
			'rank'			=>	$rank,
			'hub'			=>	$hub,
			'query'			=>	$query,
		);

        $container = new QueryService\DependencyContainer( array( 'config' => new Wikia\Search\Config( $params ) ) );
        $results = QueryService\Factory::getInstance()->get( $container )->search();

        $text = $this->app->getView('WikiaSearch', 'WikiaMobileResultList', array(
                'currentPage'=> $page,
                'isInterWiki' => $isInterWiki,
                'relevancyFunctionId' => WikiaSearch::RELEVANCY_FUNCTION_ID,
                'results' => $results,
                'resultsPerPage' => self::RESULTS_PER_PAGE,
                'query' => $query)
        )->render();

        $this->response->setVal('text', $text);
        $this->wf->ProfileOut(__METHOD__);
    }
}