<?php

namespace projectmentor\UUID;


class UUID
{ 
    /**
    * Note: Not RFC 4211 COMPLIANT !!!
    * (server_id)-(clientIP)-(unixtime)-(milliseconds)-(random)
    * 
    * Usage:
    * $u = \App\UUID::uuid()
    * echo $u;
    *     '0001-7f000001-478c8000-4801-47242987'
    *
    */
    public static function uuid($serverID=1)
    {
        $t=explode(" ",microtime());
        return sprintf( '%04x-%08s-%08s-%04s-%04x%04x',
        $serverID,
        static::clientIPToHex(),
        substr("00000000".dechex($t[1]),-8),   // get 8HEX of unixtime
        substr("0000".dechex(round($t[0]*65536)),-4), // get 4HEX of microtime
        mt_rand(0,0xffff), mt_rand(0,0xffff));
    }   

    /**
    * Note: Not RFC 4211 COMPLIANT !!!
    * (server_id)-(clientIP)-(unixtime)-(milliseconds)-(random)
    * 
    * Usage:
    * print_r(uuidDecode($u));
    *   Array ( 
    *       [serverID] => 0001,
    *       [ip] => 127.0.0.1,
    *       [unixtime] => 1200390144,
    *       [micro] => 0.28126525878906
    *   ) 
    *
    */
    public static function uuidDecode($uuid)
     {
        $rez=Array();
        $u=explode("-",$uuid);
        if(is_array($u)&&count($u)==5) {
            $rez=Array(
                'serverID'=>$u[0],
                'ip'=>static::clientIPFromHex($u[1]),
                'unixtime'=>hexdec($u[2]),
                'micro'=>(hexdec($u[3])/65536)
            );
        }   
        return $rez;
    }

    public static function clientIPToHex($ip="")
    {
        $hex="";
        if($ip=="") $ip=getEnv("REMOTE_ADDR");
        $part=explode('.', $ip);
        for ($i=0; $i<=count($part)-1; $i++) {
            $hex.=substr("0".dechex($part[$i]),-2);
        }
        return $hex;
    }   

    public static function clientIPFromHex($hex)
     {
        $ip="";
        if(strlen($hex)==8) {
            $ip.=hexdec(substr($hex,0,2)).".";
            $ip.=hexdec(substr($hex,2,2)).".";
            $ip.=hexdec(substr($hex,4,2)).".";
            $ip.=hexdec(substr($hex,6,2));
        }
        return $ip;
    }

  public static function v3($namespace, $name) {
    if(!self::is_valid($namespace)) return false;

    // Get hexadecimal components of namespace
    $nhex = str_replace(array('-','{','}'), '', $namespace);

    // Binary Value
    $nstr = '';

    // Convert Namespace UUID to bits
    for($i = 0; $i < strlen($nhex); $i+=2) {
      $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
    }

    // Calculate hash value
    $hash = md5($nstr . $name);

    return sprintf('%08s-%04s-%04x-%04x-%12s',

      // 32 bits for "time_low"
      substr($hash, 0, 8),

      // 16 bits for "time_mid"
      substr($hash, 8, 4),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 3
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

      // 48 bits for "node"
      substr($hash, 20, 12)
    );
  }

  public static function v4() {
    return strtoupper(sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    ));
  }

  public static function v5($namespace, $name) {
    if(!self::is_valid($namespace)) return false;

    // Get hexadecimal components of namespace
    $nhex = str_replace(array('-','{','}'), '', $namespace);

    // Binary Value
    $nstr = '';

    // Convert Namespace UUID to bits
    for($i = 0; $i < strlen($nhex); $i+=2) {
      $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
    }

    // Calculate hash value
    $hash = sha1($nstr . $name);

    return sprintf('%08s-%04s-%04x-%04x-%12s',

      // 32 bits for "time_low"
      substr($hash, 0, 8),

      // 16 bits for "time_mid"
      substr($hash, 8, 4),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 5
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

      // 48 bits for "node"
      substr($hash, 20, 12)
    );
  }

  public static function is_valid($uuid) {
    return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                      '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
  }
}
