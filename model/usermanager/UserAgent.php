<?php


namespace model\usermanager;


class UserAgent
{
    public static function getBrowser($httpUserAgent) : string {
        if(preg_match('/MSIE/i', $httpUserAgent) && !preg_match('/Opera/i',$httpUserAgent))
            return "Internet Explorer";
        elseif(preg_match('/Firefox/i',$httpUserAgent))
            return "Mozilla Firefox";
        elseif(preg_match('/OPR/i',$httpUserAgent))
            return "Opera";
        elseif(preg_match('/Chrome/i',$httpUserAgent) && !preg_match('/Edge/i',$httpUserAgent))
            return "Google Chrome";
        elseif(preg_match('/Safari/i',$httpUserAgent) && !preg_match('/Edge/i',$httpUserAgent))
            return "Apple Safari";
        elseif(preg_match('/Netscape/i',$httpUserAgent))
            return "Netscape";
        elseif(preg_match('/Edge/i',$httpUserAgent))
            return "Edge";
        elseif(preg_match('/Trident/i',$httpUserAgent))
            return 'Internet Explorer';
    }

public static function getOS($user_agent) {
    $os_array = array(
        '/windows nt 10/i'      =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os = $value;

    return $os;
    }
}