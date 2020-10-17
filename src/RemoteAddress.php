<?php
/*
* File: RemoteAddress.php
* Category: -
* Author: M.Goldenbaum
* Created: 17.10.20 23:21
* Updated: -
*
* Description:
*  -
*/

/**
 * @see       https://github.com/zendframework/zend-http for the canonical source repository
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-http/blob/master/LICENSE.md New BSD License
 */

namespace Webklex\GeoIP;


/**
 * Class RemoteAddress
 *
 * @package App\Libraries\Analyzer
 */
class RemoteAddress {

    /**
     * Whether to use proxy addresses or not.
     *
     * As default this setting is disabled - IP address is mostly needed to increase
     * security. HTTP_* are not reliable since can easily be spoofed. It can be enabled
     * just for more flexibility, but if user uses proxy to connect to trusted services
     * it's his/her own risk, only reliable field for IP address is $_SERVER['REMOTE_ADDR'].
     *
     * @var bool
     */
    protected $useProxy = false;

    /**
     * List of trusted proxy IP addresses
     *
     * @var array
     */
    protected $trustedProxies = [];

    /**
     * HTTP header to introspect for proxies
     *
     * @var string
     */
    protected $proxyHeader = 'HTTP_X_FORWARDED_FOR';

    /**
     * Changes proxy handling setting.
     *
     * This must be static method, since validators are recovered automatically
     * at session read, so this is the only way to switch setting.
     *
     * @param  bool $useProxy Whether to check also proxied IP addresses.
     * @return RemoteAddress
     */
    public function setUseProxy($useProxy = true) {
        $this->useProxy = $useProxy;
        return $this;
    }

    /**
     * Checks proxy handling setting.
     *
     * @return bool Current setting value.
     */
    public function getUseProxy() {
        return $this->useProxy;
    }

    /**
     * Set list of trusted proxy addresses
     *
     * @param  array $trustedProxies
     * @return RemoteAddress
     */
    public function setTrustedProxies(array $trustedProxies) {
        $this->trustedProxies = $trustedProxies;
        return $this;
    }

    /**
     * Set the header to introspect for proxy IPs
     *
     * @param  string $header
     * @return RemoteAddress
     */
    public function setProxyHeader($header = 'X-Forwarded-For') {
        $this->proxyHeader = $this->normalizeProxyHeader($header);
        return $this;
    }

    /**
     * Returns client IP address.
     *
     * @return string IP address.
     */
    public function getIpAddress() {
        $ip_address = $this->getIpAddressFromProxy();

        if($ip_address == "127.0.0.1" || strlen($ip_address) < 7) {
            $ip_address = false;
        }

        if ($ip_address) {
            return $ip_address;
        }

        $ip_address = $this->getServerVariable("REMOTE_ADDR");

        if(strlen($ip_address) < 7) {
            $ip_address = "127.0.0.1";
        }

        // direct IP address
        return $ip_address;
    }

    /**
     * Attempt to get the IP address for a proxied client
     *
     * @see http://tools.ietf.org/html/draft-ietf-appsawg-http-forwarded-10#section-5.2
     * @return false|string
     */
    protected function getIpAddressFromProxy() {
        if (!$this->useProxy
            || ($this->getServerVariable('REMOTE_ADDR') != null && !in_array($this->getServerVariable('REMOTE_ADDR'), $this->trustedProxies))
        ) {
            return false;
        }

        $header = $this->proxyHeader;
        if (!$this->getServerVariable($header)) {
            return false;
        }

        // Extract IPs
        $ips = explode(',', $this->getServerVariable($header));
        // trim, so we can compare against trusted proxies properly
        $ips = array_map('trim', $ips);
        // remove trusted proxy IPs
        $ips = array_diff($ips, $this->trustedProxies);

        // Any left?
        if (empty($ips)) {
            return false;
        }

        // Since we've removed any known, trusted proxy servers, the right-most
        // address represents the first IP we do not know about -- i.e., we do
        // not know if it is a proxy server, or a client. As such, we treat it
        // as the originating IP.
        // @see http://en.wikipedia.org/wiki/X-Forwarded-For
        $ip = array_pop($ips);
        return $ip;
    }

    /**
     * Normalize a header string
     *
     * Normalizes a header string to a format that is compatible with
     * $_SERVER
     *
     * @param  string $header
     * @return string
     */
    protected function normalizeProxyHeader($header) {
        $header = strtoupper($header);
        $header = str_replace('-', '_', $header);
        if (0 !== strpos($header, 'HTTP_')) {
            $header = 'HTTP_' . $header;
        }
        return $header;
    }

    protected function getServerVariable($key, $default = null){
        if (!isset($_SERVER[$key]) || empty($_SERVER[$key])) {
            return $default;
        }

        return $_SERVER[$key];
    }

    /**
     * @return bool
     */
    public function isProxy(){
        $test_HTTP_proxy_headers = [
            'HTTP_VIA',
            'VIA',
            'Proxy-Connection',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED',
            'HTTP_CLIENT_IP',
            'HTTP_FORWARDED_FOR_IP',
            'X-PROXY-ID',
            'MT-PROXY-ID',
            'X-TINYPROXY',
            'X_FORWARDED_FOR',
            'FORWARDED_FOR',
            'X_FORWARDED',
            'FORWARDED',
            'CLIENT-IP',
            'CLIENT_IP',
            'PROXY-AGENT',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'FORWARDED_FOR_IP',
            'HTTP_PROXY_CONNECTION'
        ];

        foreach($test_HTTP_proxy_headers as $header){
            if ($this->getServerVariable($header, false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $user_agent
     * @return false|int
     */
    public function isCrawler($user_agent) {
        return preg_match('/bot|crawl|slurp|spider|mediapartners|bing/i', $user_agent) > 0;
    }

    /**
     * @param $hostname
     * @return null|string
     */
    public function getISP($hostname) {

        $isp = explode('.', $hostname);
        $isp = array_reverse($isp);
        $tmp = $isp[1];
        if (preg_match("/\<(org?|com?|net)\>/i", $tmp)) {
            $myisp = $isp[2].'.'.$isp[1].'.'.$isp[0];
        } else {
            $myisp = $isp[1].'.'.$isp[0];
        }
        if (preg_match("/[0-9]{1,3}\.[0-9]{1,3}/", $myisp))
            return null;

        $isp = explode('.', $myisp);
        return $isp[0];
    }

    /**
     * @param $ip_address
     * @return string
     */
    public function getHost($ip_address) {
        return gethostbyaddr($ip_address);
    }

}
