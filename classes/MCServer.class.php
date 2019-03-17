<?php

class MCServer {
	/**
	 * @return string
	 */
	public function getHostname() {
		return $this->hostname;
	}

	/**
	 * @param string $hostname
	 */
	public function setHostname( $hostname ) {
		$this->hostname = $hostname;
	}

	/**
	 * @return string
	 */
	public function getGametype() {
		return $this->gametype;
	}

	/**
	 * @param string $gametype
	 */
	public function setGametype( $gametype ) {
		$this->gametype = $gametype;
	}

	/**
	 * @return int
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @param int $version
	 */
	public function setVersion( $version ) {
		$this->version = $version;
	}

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
	public function getPlayersOnline() {
		return $this->players_online;
	}

	/**
	 * @param int $players_online
	 */
	public function setPlayersOnline( $players_online ) {
		$this->players_online = $players_online;
	}

	/**
	 * @return int
	 */
	public function getMaxPlayersSeen() {
		return $this->max_players_seen;
	}

	/**
	 * @param int $max_players_seen
	 */
	public function setMaxPlayersSeen( $max_players_seen ) {
		$this->max_players_seen = $max_players_seen;
	}

	public $hostname = '';
	public $gametype = '';
	public $version = 0;
	public $isonline = false;
	public $players_online = 0;
	public $max_players_seen = 0;
	public $players;

	/**
	 * @return mixed
	 */
	public function getPlayers() {
		return $this->players;
	}

	/**
	 * @param mixed $players
	 */
	public function setPlayers( $players ) {
		$this->players = $players;
	}

}

?>