<?php
use \Curl\Curl;

/**
 * Ivan Dyachuk
 * Website: #
 * Social profiles
 * Email: wrximpreza1987@gmail.com
 * Copyright (c) 2016. All rights
 */


/**
 * Ivan Dyachuk
 * Website: #
 * Social profiles
 * Email: wrximpreza1987@gmail.com
 * Copyright (c) 2016. All rights
 */
class urlFind implements parserInterface
{

    public $countShow;

        private $ip = '62.210.79.169';
    /**
     * urlFind constructor.
     * @param FlashMessages messages
     * @param Logger logs
     * @param $word Search word
     * @param int $limit Limit search link
     */
    public function __construct($msg, $log, $word, $limit = 1)
    {
        $this->word = $word;
        $this->word = explode(PHP_EOL, $word);

        foreach ($this->word as $w){
            if($w == ''){
                unset($w);
            }
        }

        $this->limit = $limit;
        if($this->limit>999){
            $this->limit = 999;
        }
        if($this->limit<1){
            $this->limit = 10;
        }

        if($this->limit > 100){
            $this->countShow = 100;
        }else{
            $this->countShow = $this->limit;
        }


        $this->msg = $msg;
        $this->log = $log;
    }

    /**
     * @return array
     */
    public function load()
    {

        try {

            $pageNum = 0;


            $lists = array();
            foreach ( $this->word as $w) {
                if($this->limit>100) {
                    $count = ceil($this->limit / 100);
                    //$subCount = $this->limit - (100 * $count);
                }else{
                    $count = 1;
                }
                $file = file_get_contents('https://yandex.ru/search/xml?user=clickkyfamily&key=03.399149303:3ac49a0d823c759c75cb07002b162b1e&query=php&lr=187&l10n=ru&sortby=tm.order%3Dascending&filter=strict&groupby=attr%3D%22%22.mode%3Dflat.groups-on-page%3D10.docs-in-group%3D1');
                //$file = file_get_contents('https://yandex.ru/search/xml?action=limits-info&user=clickkyfamily&key=03.399149303:3ac49a0d823c759c75cb07002b162b1e');
                //echo new SimpleXMLElement($file);
                echo $file;
                exit();

                for ($pageNum = 0; $pageNum < $count; $pageNum++) {

                    $url = 'http://www.google.com.ua/search?q=' . urlencode($w) . '&num=' . $this->countShow . '&hl=ru&start=' . $pageNum . '&ie=UTF-8';
                    
                    $curl = new Curl();

                    //$curl->setOpt(CURLOPT_HTTPPROXYTUNNEL, 1);
                    //$curl->setOpt(CURLOPT_PROXY, '80.252.240.4:8080');
                    //$curl->setOpt(CURLOPT_PROXYUSERPWD, 'username:password');

                    $curl->get(trim($url));
                    $page = $curl->response;

                    //$page = file_get_contents($url);

                    if ($curl->response != '' && $curl->errorCode !== 200) {
                        return 'Ошибка при поиске в Google. ' . $curl->errorMessage;
                    }
                
                    /*if (!strpos($http_response_header[0], "200")) {
                        //$this->msg->error('Страница вернула код '.$http_response_header[0]);
                        $this->log->error('Страница вернула код ' . $http_response_header[0]);
                        return 'Страница вернула код ' . $http_response_header[0];
                    }*/

                    /*if (!$page)
                        $page = curlgoogle($url);*/

                    if (!$curl->response) {
                        //$this->msg->error('Страница не загрузилась');
                        $this->log->error('Страница не загрузилась');
                        return 'Страница не загрузилась';
                    } else {

                        if (preg_match_all('#<cite>(.+?)</cite>#si', $page, $match)) {


                            foreach ($match['1'] as $v) {
                                $lists[] = urlencode(strip_tags($v));
                            }

                            //$this->msg->info('Данные спарсились по слову "' . $this->word . '" с google.com');

                        } else {
                            //$this->msg->error('По запросу "' . $this->word . '" линков в google.com нет.');
                            $this->log->error('По запросу "' . $this->word . '" линков в google.com нет.');
                        }

                        //return 'По запросу "' . $this->word . '" линков в google.com нет.';
                    }

                }


            }

            return $lists;


        } catch (Exception $e) {
            $this->log->error('Message: ' . $e->getMessage() . ', File: ' . $e->getFile() . ', Line: ' . $e->getLine());

            return 'Message: ' . $e->getMessage() . ', File: ' . $e->getFile() . ', Line: ' . $e->getLine();
        }

    }

    /**
     * @param $url
     * @return mixed
     */
    public function curlgoogle($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_PROXY, '88.87.72.72:8080');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_USERAGENT, '');
        curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.ru/');
        return curl_exec($curl);
    }


}


