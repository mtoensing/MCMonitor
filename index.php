<?php
$string = file_get_contents("db/data.json");
$json = json_decode($string);


echo "Server last seen " . time2str($json->server->last_seen) . "<br>";

foreach ($json->players as $player => $value) {
    echo $player ." - " . time2str($value->last_seen) . '<br>';
}


function time2str($ts)
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

