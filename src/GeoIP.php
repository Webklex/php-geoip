<?php
/*
* File:     GeoIP.php
* Category: -
* Author:   M. Goldenbaum
* Created:  17.10.20 23:21
* Updated:  -
*
* Description:
*  -
*/

namespace Webklex\GeoIP;


/**
 * Class GeoIP
 *
 * @package Webklex\PHPIMAP
 */
class GeoIP {

    /**
     * Api endpoint
     * @var string $endpoint
     */
    protected $endpoint = "https://www.gogeoip.com";

    /**
     * Session cache holder
     * @var array $cache
     */
    protected $cache = [];

    /** @var  RemoteAddress $remote_address*/
    protected $remote_address;

    /**
     * GeoIP constructor.
     * @param string $endpoint
     */
    public function __construct($endpoint = null) {
        if ($endpoint !== null) {
            $this->endpoint = $endpoint;
        }
        $this->remote_address = new RemoteAddress();
    }

    /**
     * Get the geoip information for the current ip
     *
     * @return array|null
     */
    public function current(){
        return $this->get($this->remote_address->getIpAddress());
    }

    /**
     * Get geoip information for a given ip
     * @param string $ip
     *
     * @return array|null
     */
    public function get($ip){
        if (isset($this->cache[$ip])) {
            return $this->cache[$ip];
        }

        return $this->cache[$ip] = $this->call($ip);
    }


    /**
     * Call the api and parse the response
     * @param string $ip
     * @param string $type
     * @param integer $retries
     *
     * @return array|boolean
     */
    protected function call($ip, $type = 'json', $retries = 3) {
        do {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->endpoint.'/'.$type.'/'.$ip);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $output = curl_exec($ch);
            curl_close($ch);

            if($output == false) {
                sleep(1);
            }else{
                return json_decode($output, true);
            }

            $retries++;
        }
        while($retries > 0);

        return null;
    }
}
