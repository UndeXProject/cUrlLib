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
    public $UA_LIST = array(
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36'
    );
    public $REF_LIST = array(
        'https://vk.com/',
        'https://google.com/',
        'https://2ip.ru/',
        'https://pikabu.ru/',
        'https://spaces.ru/'
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
        if($gzip)
            return gzdecode(curl_exec($this->C));
        else
            return curl_exec($this->C);
    }
}
