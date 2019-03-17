<?php

class MCPlayer {
	/**
	 * @return bool
	 */
	public function isIsonline() {
		return $this->isonline;
	}

	/**
	 * @param bool $isonline
	 */
	public function setIsonline( $isonline ) {
		$this->isonline = $isonline;
	}

	/**
	 * @return int
	 */
	public function getLastseen() {
		return $this->lastseen;
	}

	/**
	 * @param int $lastseen
	 */
	public function setLastseen( $lastseen ) {
		$this->lastseen = $lastseen;
	}

	public $isonline = false;
	public $lastseen = 0;

}


?>