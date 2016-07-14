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
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
class urlFind implements parserInterface
{

    public $countShow;

    private $ip = '62.210.79.169';

    private $yandexKey = '03.399149303:3ac49a0d823c759c75cb07002b162b1e';

    private  $yandexUser = 'clickkyfamily';

    /**
     * @var string
     */
    public $file = 'file/search_at_this_moment.db';

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
        $this->input = new Request();


        foreach ($this->word as $w) {
            if ($w == '') {
                unset($w);
            }
        }

        $this->word =  array_splice($this->word, -5);

        $this->limit = $limit;
        if ($this->limit > 100) {
            $this->limit = 100;
        }
        if ($this->limit < 1) {
            $this->limit = 10;
        }

        if ($this->limit > 100) {
            $this->countShow = 100;
        } else {
            $this->countShow = $this->limit;
        }
        $this->msg = $msg;
        $this->log = $log;

        $this->db = new SQLite3($this->file);
        //$this->db->exec('DROP TABLE needSend');
        $this->db->exec('CREATE TABLE IF NOT EXISTS needSend (email VARCHAR(255), sites VARCHAR(255), time VARCHAR(255), status VARCHAR(255), count VARCHAR(255))');


    }

    /**
     * @return array
     */
    public function load()
    {

        try {


            $pageNum = 0;
            $lists = array();
            if ($this->word) {

                foreach ($this->word as $w) {
                    if ($this->limit > 100) {
                        $count = ceil($this->limit / 100);
                        //$subCount = $this->limit - (100 * $count);
                    } else {
                        $count = 1;
                    }


                    $file = file_get_contents('https://yandex.com/search/xml?user='.$this->yandexUser.'&key='.$this->yandexKey.'&query='.urlencode($w).'&l10n=en&lr=187&sortby=rlvfilter=strict&groupby=attr%3D%22%22.mode%3Dflat.groups-on-page%3D'.$this->limit.'.docs-in-group%3D1');
                    $xml = new SimpleXMLElement($file);
                    $results = $xml->response->results->grouping;

                    if($results) {
                        foreach ($results->group as $result) {
                            /*$lists[] = array(
                                            urlencode(strip_tags($result->doc->url)),
                                            urlencode($result->doc->title)
                                        );*/
                            $lists[] = urlencode(strip_tags($result->doc->url));
                        }
                    }else{

                        $this->db->exec("INSERT INTO needSend (`email`, `sites`, `time`, `status`, `count`)
                       VALUES ('" . $this->input->post('email') . "','" . serialize($this->word) . "', '" . time() . "', '0','" . $this->input->post('count') . "')");
                       if ($this->db->lastErrorMsg() != 'not an error') {
                           return  $this->db->lastErrorMsg();
                       }


                        return 'Лимит запросов исчерпан у пользователя. Ваш запрос добавлен в список задач. Как только будет будет возможности 
                мы сделаем запрос и отправим вам на почту. ';
                    }



                    /*for ($pageNum = 0; $pageNum < $count; $pageNum++) {

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

                    }*/


                }
            } else {
                return 'Вы ничего не ввели';
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

    private function getLimit(){

        $file = file_get_contents('https://yandex.ru/search/xml?action=limits-info&user='.$this->yandexUser.'&key='.$this->yandexKey);

        $limit = simplexml_load_string($file);

        $limit = (array)$limit->response->limits;
        $limit = $limit['time-interval'];

        return $limit[date('H')-3];

    }

}


