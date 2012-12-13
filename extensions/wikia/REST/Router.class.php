<?php

namespace REST;

final class Router {
	protected $route;
	protected $data;

	function __construct() {
		$this->route = null;
		$this->data = null;
	}

	public function run() {
		if ( !is_null( $this->route ) ) {
			return $this->route->resolve( $this->data );
		} else {
			throw new Exception( 'No route' );
		}
	}

	public function setRoute( Route $route ) {
		$this->route = $route;
	}

	public function getRoute() {
		return $this->route;
	}

	public function setData( $data ) {
		$this->data = $data;
	}

	public function getData() {
		return $this->data;
	}
}