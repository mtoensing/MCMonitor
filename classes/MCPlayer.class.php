<?php

class MCPlayer
{

    public $isonline = false;
    public $last_seen = 0;

    /**
     * @return bool
     */
    public function isIsonline()
    {
        return $this->isonline;
    }

    /**
     * @param bool $isonline
     */
    public function setIsonline($isonline)
    {
        $this->isonline = $isonline;
    }

    /**
     * @return int
     */
    public function getLastseen()
    {
        return $this->last_seen;
    }

    /**
     * @param int $lastseen
     */
    public function setLastseen($last_seen)
    {
        $this->last_seen = $last_seen;
    }


}


?>