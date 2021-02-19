<?php
/*
    cURL lib by Und3X
    Site: https://und3x.ru/
    Git: https://github.com/UndeXProject
    Repository: https://github.com/UndeXProject/cUrlLib
*/

class cUrlLib {
    // Public variables
    public $URL;
    public $UA;
    public $REF;
    public $COOKIE;
    public $CACHE           =   false;      // Cache engine status
    public $CACHE_DIR       =   __DIR__;    // Default cache directory
    /* No cache MIME */
    public $CACHE_FILER     =   "";
    public $CACHE_MAX_SIZE  =   1048576;    // Max cache file size (bytes). Zero - unlimited. 1048576 - 1 Mb.
    public $UA_LIST = array(
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36'
    );
    public $REF_LIST = array(
        "http://www.google.com/",
		"http://pastebin.com",
		"http://www.yandex.ru/",
		"http://www.yahoo.com/",
		"http://www.youtube.ru/",
		"http://www.carderlife.ms/",
		"http://www.hacker-pro.net/",
		"http://www.host-tracker.com/",
		"http://www.forum.antichat.ru/",
		"http://www.lenta.ru/",
		"http://www.wikpedia.org/",
		"http://www.mail.ru/",
		"http://www.vkontakte.ru/",
		"http://www.upyachka.ru/",
		"http://www.2ip.ru/",
		"http://www.webmoney.ru/",
		"http://www.live.com/",
		"http://www.libertyreserve.com/",
		"http://www.ebay.com/",
		"http://www.microsoft.com/",
		"http://www.ninemsn.com/",
		"http://oce.leagueoflegends.com/",
		"http://aftamat4ik.ru/",
		"http://vk.com/",
		"http://facebook.com/",
		"http://twitter.com/",
		"https://www.dropbox.com/"
    );

    // Private variables
    private $C;
    private $WORK = false;

    // Construction function
    public function __construct($cookie_dir, $url = false){
        if($url) $this->URL = $url;
        $this->COOKIE = $cookie_dir;
        $this->C = curl_init();
        $this->setDefaultOptions();
        $this->WORK = true;
    }


    /*
        Default cURL option set
    */
    public function setDefaultOptions(){
        // Randomize useragent and referer
        $this->UA = $this->UA_LIST[array_rand($this->UA_LIST)];
        $this->REF = $this->REF_LIST[array_rand($this->REF_LIST)];

        // Default browser headers
        $header[0] = "Accept: text/html,application/xhtml+xml,application/xml;";
        $header[0] .= "q=0.9,image/avif,image/webp,image/apng,*/*;q=";
        $header[0] .= "0.8,application/signed-exchange;v=b3;q=0.9";
        $header[] = "Accept-Encoding: br, gzip, deflate";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7";
        $header[] = "Pragma: "; // browsers keep this blank.

        try {
            curl_setopt($this->C, CURLOPT_HTTPHEADER, $header);
            curl_setopt($this->C, CURLOPT_USERAGENT, $this->UA);
            curl_setopt($this->C, CURLOPT_REFERER, $this->REF);
            curl_setopt($this->C, CURLOPT_TIMEOUT, 30);
            curl_setopt($this->C, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->C, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->C, CURLOPT_HEADER, false);
            curl_setopt($this->C, CURLOPT_COOKIESESSION, true);
            curl_setopt($this->C, CURLOPT_COOKIEJAR, $this->COOKIE);
            curl_setopt($this->C, CURLOPT_COOKIEFILE, $this->COOKIE);
        }catch (Exception $e){
            throw new Exception('cUrlLib: Called exception: '.$e->getMessage());
        }
        return true;
    }

    /*
        Custom cURL option set
        (See: https://www.php.net/manual/ru/function.curl-setopt.php)

        Param $option   [constant*]     -   cURL option constant
        Param $value    [mixed]         -   Option value (Bool/String/Integer)

        Return [bool]                   -   Status set option
    */
    public function setOption($option, $value){
        return curl_setopt($this->C, $option, $value);
    }

    /*
        Get cURL connection information
        (See: https://www.php.net/manual/ru/function.curl-getinfo.php)

        Param $option   [mixed]         -   cURL info constant or FALSE

        Return          [mixed]         -   Option information or all data
    */
    public function getInfo($option = false){
        if($option){
            return curl_getinfo($this->C, $option);
        }else{
            return curl_getinfo($this->C);
        }
    }

    /*
        Open cURL connection

        Return [CurlHandle]     -   cUrl handle
    */
    public function open(){
        if(!$this->WORK){
            $this->WORK = true;
            return curl_init($this->C);
        }else{
            throw new Exception('cUrlLib: Connection already opened!');
        }
    }

    /*
        Close cURL connection
    */
    public function close(){
        if($this->WORK){
            $this->WORK = false;
            return curl_close($this->C);
        }else{
            throw new Exception('cUrlLib: Connection already closed!');
        }
    }

    /*
        Get cache data

        Param $link [string]        -   URL adress

        Return      [mixed]         -   Cache data from url or FALSE
    */
    public function getCacheData($link){
        $name = md5($link);
        if(file_exists($this->CACHE_DIR.'/'.$name)){
            $data = file_get_contents($this->CACHE_DIR.'/'.$name);
            return gzdecode($data);
        }else{
            return false;
        }
    }


    /*
        Save link to cache

        Param $link [string]        -   URL adress
        Param $data [string]        -   Data
    */
    private function saveCacheData($link, $data){
        $name = md5($link);
        file_put_contents($this->CACHE_DIR.'/'.$name, $data);
    }

    /*
        Get data

        Param $url  [mixed]     - False or url
        Param $gzip [bool]      - Enable gZip decompress

        Return [string]         - HTML or other data
    */
    public function get($url = false, $gzip = true){
        if($url){
            if(empty($url)) throw new Exception('cUrlLib: URL is empty!');
            $this->URL = $url;
        }else
            if(empty($this->URL)) throw new Exception('cUrlLib: URL is empty!');
        curl_setopt($this->C, CURLOPT_URL, $this->URL);

        $data = curl_exec($this->C);
        if($data === false){
            if($this->CACHE){
                $data = $this->getCacheData($this->URL);
            }else{
                $data = false;
            }
        }else{
            if($this->CACHE){
                $size = intval($this->getInfo(CURLINFO_SIZE_DOWNLOAD));
                $mime = $this->getInfo(CURLINFO_CONTENT_TYPE);
                // Check size
                if($this->CACHE_MAX_SIZE!=0 && $size<=$this->CACHE_MAX_SIZE){
                    // Check mime
                    if(!in_array($mime, explode("|",$this->CACHE_FILER))){
                        $this->saveCacheData($this->URL, ($gzip ? $data : gzencode($data)));
                    }
                }
            }
            return ($gzip ? gzdecode($data) : $data);
        }
    }
}
