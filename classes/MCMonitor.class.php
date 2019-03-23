<?php

/**
 *
 * Marc Tönsing 2019
 *
 * Class MCMonitor
 */

class MCMonitor
{

    public $query;
    public $online = false;
    public $online_status = 'offline';
    public $address = '';
    public $port = 25565;
    public $hostname = '';
    public $timeout = 1;
    public $error = Array();
    public $json = '';

    public $info;

    public $gametype = '';
    public $version = 0;
    public $players_online = 0;
    public $max_players_seen = 0;
    public $players;

    public $meta = Array();

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error[] = $error;
    }


    public function __construct($address, $port, $timeout)
    {

        $this->address = $address;
        $this->port = $port;
        $this->timeout = $timeout;

        $this->connect();
        $this->getDatafromDB();

        if ($this->error != false) {
            echo $this->getError();
        }

        $this->fillPlayers($this->json->players);
        $this->fillPlayers($this->query->GetPlayers(), true);
        $this->fillMeta();

        arsort($this->players);

    }

    public function saveJSON()
    {
        if ($this->error == false) {
            file_put_contents('db/data.json', $this->getJSON());
        } else {
            echo $this->getError();
        }
    }

    public function fillMeta()
    {
        $arr = Array();

        $arr['hostname'] = $this->hostname;
        $arr['address'] = $this->address;
        $arr['gametype'] = $this->json->server->gametype;
        $arr['version'] = $this->json->server->version;

        if ($this->isOnline()) {
            $arr['isonline'] = true;
            $arr['last_seen'] = time();
            $arr['players_online'] = $this->getOnlinePlayerNumber();

            if ($this->getOnlinePlayerNumber() > $this->json->server->max_seen_online) {
                $arr['max_seen_online'] = $this->getOnlinePlayerNumber();
            } else {
                $arr['max_seen_online'] = $this->json->server->max_seen_online;
            }
        } else {

            $arr['isonline'] = false;
            $arr['players_online'] = 0;
            $arr['last_seen'] = $this->json->server->last_seen;
            $arr['max_seen_online'] = $this->json->server->max_seen_online;
        }

        $this->meta = $arr;

    }

    public function getJSON()
    {

        $arr = array();

        $arr['server'] = $this->meta;

        $arr['players'] = $this->players;

        return json_encode($arr, JSON_PRETTY_PRINT);

    }

    public function getOnlinePlayerNumber()
    {

        $this->fillPlayers($this->json->players);
        $this->fillPlayers($this->query->GetPlayers(), true);

        $players = $this->players;

        $count = 0;

        foreach ($players as $player) {
            if ($player->isonline == true) {
                $player->last_seen = time();
                $count++;
            }
        }

        return $count;
    }


    /**
     * @return string
     */
    public function getError()
    {

        if (count($this->error) > 0) {
            foreach ($this->error as $error_msg) {
                $errors_html = $error_msg . '<br>';
            }
            return $errors_html;
        } else {
            return false;
        }

    }

    public function getDatafromDB()
    {
        $string = file_get_contents("db/data.json");

        if ($string === false) {
            $this->setError("Unable to read data.");
        }

        if (!$this->isJson($string)) {
            $this->setError("JSON is invalid");
        } else {
            $json = json_decode($string);
            $this->json = $json;
        }

        $this->getLIVEData();

    }


    public function fillPlayers($players, $online_status = false)
    {
        if ($players !== false) {

            foreach ($players as $player => $player_value) {

                $player_name = htmlspecialchars($player);
                if (is_array($players)) {
                    $player_name = htmlspecialchars($player_value);
                }

                $player_obj = new MCPlayer();

                $player_obj->setLastseen(time());
                if ($online_status == false) {
                    $player_obj->setLastseen($player_value->last_seen);
                }

                $player_obj->setIsonline($online_status);

                $this->players[$player_name] = $player_obj;

            }
        }

    }

    private function isJson($string)
    {
        $decoded = json_decode($string);
        if (!is_object($decoded) && !is_array($decoded)) {
            return false;
        }

        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function connect()
    {
        $this->query = new MinecraftQuery();
        try {
            $this->query->Connect($this->address, $this->port, $this->timeout);

            $this->setOnline(true);
        } catch (MinecraftQueryException $e) {
            $this->setOnline(false);
        }
    }


    public function getLIVEData()
    {

        $this->info = $this->query->GetInfo();
        $this->version = $this->info["Version"];
        $this->hostname = $this->info["HostName"];
        $this->getLIVEGameType();


    }


    public function getLIVEGameType()
    {

        $gametype_raw = $this->info["GameType"];

        switch ($gametype_raw) {
            case 'SMP':
                $this->gametype = "Survival Multiplayer";
                break;
            case 'CRE':
                $this->gametype = "Creative Mode";
                break;
            case 'ADV':
                $this->gametype = "ADVENTURE";
                break;
            default:
                $this->gametype = "???";
        }

    }


    /**
     * @return bool
     */
    public function isOnline()
    {
        return $this->online;
    }

    /**
     * @param bool $online
     */
    public function setOnline($online)
    {
        $this->online = $online;
    }

    /**
     * @return string
     */
    public function getOnlineStatus()
    {

        if ($this->isOnline()) {
            return "online";
        } else {
            return "offline";
        }
    }



}

?>
