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

                for ($pageNum = 0; $pageNum < $count; $pageNum++) {

                    $url = 'http://www.google.ru/search?q=' . urlencode($w) . '&num=' . $this->countShow . '&hl=ru&start=' . $pageNum . '&ie=UTF-8';


                    $page = file_get_contents($url);

                    if (!strpos($http_response_header[0], "200")) {
                        //$this->msg->error('Страница вернула код '.$http_response_header[0]);
                        $this->log->error('Страница вернула код ' . $http_response_header[0]);
                        return 'Страница вернула код ' . $http_response_header[0];
                    }

                    if (!$page)
                        $page = curlgoogle($url);

                    if (!$page) {
                        //$this->msg->error('Страница не загрузилась');
                        $this->log->error('Страница не загрузилась');
                        //return 'Страница не загрузилась';
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


