<?php

class MCViewer
{
    public $json = '';
    public $address ='';
    public $hostname ='';
    public $gametype ='';
    public $version ='';
    public $isonline = '';
    public $tpl;
    public $overviewer_url = '';
    public $overviewer_path = false;

    public function __construct($json_path)
    {
        $string = file_get_contents($json_path);
        $this->json = json_decode($string);

        $this->version = $this->json->server->version;
        $this->gametype = $this->json->server->gametype;
        $this->isonline = $this->json->server->isonline;
        $this->address = $this->json->server->address;
        $this->hostname = $this->json->server->hostname;
        $this->players_online = $this->json->server->players_online;
        $this->max_seen_online = $this->json->server->max_seen_online;


    }

    /**
     * @param bool $overviewer_path
     */
    public function setOverviewerPath($overviewer_path)
    {
        $this->overviewer_path = $overviewer_path;
    }



    public function getMapCreatedTime()
    {
        if($this->overviewer_path){

            $fullpath = $this->overviewer_path. '/index.html';

            if (file_exists($fullpath)) {

                $map_ts = filemtime($fullpath);
                $map_created_time = $this->time2str($map_ts);

                return '(generated ' . $map_created_time.')';
            }
        }
    }

    /**
     * @return mixed
     */
    public function getOverviewerUrl()
    {
        return $this->overviewer_url;
    }

    /**
     * @param mixed $overviewer_url
     */
    public function setOverviewerUrl($overviewer_url)
    {
        $this->overviewer_url = $overviewer_url;
    }

    public function getOutput($overviewer_url = '')
    {
        $this->setOverviewerUrl($overviewer_url);


        $this->fillTemplate();
        $html = $this->tpl->output();

        if ($html) {
            return $html;
        } else {
            return false;
        }
    }

    public function fillTemplate()
    {
        $this->tpl = new MCTemplate("tpl/template.html");
        $this->tpl->set("playerlist", $this->getPlayerList());
        $this->tpl->set("isonline", $this->getOnlineStatus());
        $this->tpl->set("version", $this->getVersion());
        $this->tpl->set("address", $this->getAddress());
        $this->tpl->set("hostname", $this->getHostname());
        $this->tpl->set("gametype", $this->getGametype());
        $this->tpl->set("players_online", $this->getPlayersOnline());
        $this->tpl->set("max_seen_online", $this->getMaxSeenOnline());
        $this->tpl->set("overviewer_url", $this->overviewer_url);

        $created_time = $this->getMapCreatedTime();

        if($created_time){
            $this->tpl->set("overviewer_last_updated", $created_time);
        } else {
            $this->tpl->set("overviewer_last_updated", '');
        }

    }

    public function getProgressMessage($filepath)
    {
        if (file_exists($filepath)) {
            $file = file_get_contents($filepath);
            $json = json_decode($file);
            $message = $json->message;
            return $message;
        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getMaxSeenOnline()
    {
        return $this->max_seen_online;
    }

    /**
     * @return mixed
     */
    public function getPlayersOnline()
    {
        return $this->players_online;
    }



    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @return string
     */
    public function getGametype()
    {
        return $this->gametype;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getOnlineStatus()
    {
        if ($this->isonline) {
            $status = 'online';
        } else {
            $status = 'offline';
        }

        return $status;
    }



    public function getPlayers()
    {
        $html = '';

        foreach ($this->json->players as $playername => $player_meta) {
            if ($player_meta->isonline) {
                $status_class = 'online';
                $status = 'online';
            } else {
                $status_class = 'offline disabled';
                $status = $this->time2str($player_meta->last_seen);
            }

            $html .= "<tr>\n";
            $html .= '<td>' . $playername . ' <span class="' . $status_class . '">(' . $status . ')</span></td>';
            $html .= "</tr>\n";
        }

        return $html;
    }

    public function getPlayerList()
    {
        $html = '<div class="list-group">';

        foreach ($this->json->players as $playername => $player_meta) {
            if ($player_meta->isonline) {
                $status_class = '';
                $status = 'online';
                $status_user_class = 'text-success';
            } else {
                $status_class = 'disabled';
                $status = $this->time2str($player_meta->last_seen);
                $status_user_class = '';
            }

            $html .= '<div class="list-group-item flex-column ' . $status_class . '">';
            $html .= '<div class="d-flex w-100 justify-content-between"><div class="mb-1 "><strong>' . $playername . '</strong></div><div class="' . $status_user_class . '">' . $status . '</div></div>';
            $html .= "</div>\n";
        }

        $html .= '</div>';

        return $html;
    }

    public function time2str($ts)
    {
        if ($ts == 'never') {
            return 'never';
        }
        if (!ctype_digit($ts)) {
            $ts = strtotime($ts);
        }
        $diff = time() - $ts;
        if ($diff == 0) {
            return 'now';
        } elseif ($diff > 0) {
            $day_diff = floor($diff / 86400);
            if ($day_diff == 0) {
                if ($diff < 60) {
                    return 'just now';
                }
                if ($diff < 120) {
                    return '1 minute ago';
                }
                if ($diff < 3600) {
                    return floor($diff / 60) . ' minutes ago';
                }
                // compare with current time to see if it was posted yesterday
                if (date('H') < ($diff / 3600)) {
                    return 'Yesterday';
                }
                // if today
                if ($diff < 7200) {
                    return '1 hour ago';
                }
                if ($diff < 86400) {
                    return floor($diff / 3600) . ' hours ago';
                }
            }
            if ($day_diff == 1) {
                return 'Yesterday';
            }
            if ($day_diff < 7) {
                return $day_diff . ' days ago';
            }
            if ($day_diff < 31) {
                return ceil($day_diff / 7) . ' weeks ago';
            }
            if ($day_diff < 60) {
                return 'last month';
            }

            return date('F Y', $ts);
        } else {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if ($day_diff == 0) {
                if ($diff < 120) {
                    return 'in a minute';
                }
                if ($diff < 3600) {
                    return 'in ' . floor($diff / 60) . ' minutes';
                }
                if ($diff < 7200) {
                    return 'in an hour';
                }
                if ($diff < 86400) {
                    return 'in ' . floor($diff / 3600) . ' hours';
                }
            }
            if ($day_diff == 1) {
                return 'Tomorrow';
            }
            if ($day_diff < 4) {
                return date('l', $ts);
            }
            if ($day_diff < 7 + (7 - date('w'))) {
                return 'next week';
            }
            if (ceil($day_diff / 7) < 4) {
                return 'in ' . ceil($day_diff / 7) . ' weeks';
            }
            if (date('n', $ts) == date('n') + 1) {
                return 'next month';
            }


            return date('F Y', $ts);
        }
    }
}
