<?php
/**
 * Created by PhpStorm.
 * User: mtoe
 * Date: 2019-03-31
 * Time: 15:03
 */

class MCOverviewer {

	public $overviewer_path = "iso";

	public function __construct() {

	}


	/**
	 * @param bool $overviewer_path
	 */
	public function setOverviewerPath( $overviewer_path ) {
		$this->overviewer_path = $overviewer_path;
	}

	public function getBetween( $content, $start, $end ) {
		$r = explode( $start, $content );
		if ( isset( $r[1] ) ) {
			$r = explode( $end, $r[1] );

			return $r[0];
		}

		return false;
	}

	public function getRenderStatus() {

		$fullpath = $this->overviewer_path . '/progress.json';

		if ( file_exists( $fullpath ) ) {
			$json = json_decode( file_get_contents( $this->overviewer_path . '/progress.json' ) );

			if ( strpos( $json->message, 'ETA' ) !== false ) {
				return $this->getBetween( $json->message, '(', ')' );
			}
		}

		return false;

	}

	public function getMapCreatedTS() {
		if ( $this->overviewer_path ) {

			$fullpath = $this->overviewer_path . '/index.html';

			if ( file_exists( $fullpath ) ) {

				$map_ts = filemtime( $fullpath );

				return $map_ts;
			}
		}

		return false;
	}

}