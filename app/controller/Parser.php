<?php
/**
 * Ivan Dyachuk
 * Website: #
 * Social profiles
 * Email: wrximpreza1987@gmail.com
 * Copyright (c) 2016. All rights
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use \Curl\Curl;

/**
 * Class Parser
 */
class Parser
{

    /**
     * @var retunr object
     */
    private $output;
    /**
     * @var
     */
    public $lists;

    /**
     * @var
     */
    public $log;
    public $msg;
    /**
     * @var array can by contact page
     */
    /*public $contactUrl = array(
        '',
        '/contacts',
        '/contact',
        '/feedback',
        '/contact.html'
    );*/
    public $inTetxContactUrl = array(
        'contacts',
        'contact',
        'feedback',
        'Контакты'
    );

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $this->log = new Logger('parser');
        $this->log->pushHandler(new StreamHandler(_ROOT . '/logs/log.log', Logger::WARNING));
        $this->input = new Request();
        $msg = new \Plasticbrain\FlashMessages\FlashMessages();

        $msg->setCssClassMap([
            $msg::INFO => 'yellow',
            $msg::SUCCESS => 'green',
            $msg::WARNING => 'red',
            $msg::ERROR => 'red',
        ]);

        $this->msg = $msg;

        $this->msg->setMsgWrapper("<p class='%s'>%s</p>");
        $this->msg->setCloseBtn('');

    }

    /**
     * @param parserInterface $outputType
     */
    public function setOutput(parserInterface $outputType)
    {
        $this->output = $outputType;
    }

    /**
     * @return mixed
     */
    public function loadOutput()
    {
        $load = $this->output->load();

        if (!$load) {
            return false;
        }else{

            $data = new stdClass();
            if(!is_array($load)){
                $data->error = $load;
                $load = array();
            }

            $data->items = $load;
            $data->page = 1;

            $data->totapages = count($load);
            header("Content-type: application/json; charset=utf-8");
            header("Cache-Control: must-revalidate");
            header("Pragma: no-cache");
            header("Expires: -1");

            try {
                $json = json_encode($data);
                if($json){
                    print $json;
                }else{
                    $this->validateJson(json_last_error());
                }

            }catch (Exception $e){
                echo $e->getMessage();
            }

            exit();

        }
        /*$this->lists = $this->checkEmail($load);
        return $this->createExcel($this->lists);*/
    }

    /**
     * Create from array of emails xlsx files
     * @param data Array of emails
     * @return mixed link on the file
     */
    protected function createExcel($data)
    {

        try {
            // Создаем объект класса PHPExcel
            $xls = new PHPExcel();
            // Устанавливаем индекс активного листа
            $xls->setActiveSheetIndex(0);
            // Получаем активный лист
            $sheet = $xls->getActiveSheet();
            $sheet->setTitle('Emails');
            $sheet->setCellValue("A1", 'URl');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $sheet->setCellValue("B1", 'Title');
            $sheet->getStyle('B1')->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $sheet->setCellValue("C1", 'Email');
            $sheet->getStyle('C1')->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $sheet->setCellValue("D1", 'Status');
            $sheet->getStyle('D1')->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $i = 2;
            foreach ($data as $item) {
                $sheet->setCellValueByColumnAndRow(
                    0,
                    $i,
                    urldecode($item->url)
                );
                $sheet->setCellValueByColumnAndRow(
                    1,
                    $i,
                    urldecode($item->title)
                );
                $sheet->setCellValueByColumnAndRow(
                    2,
                    $i,
                    $item->email
                );
                $sheet->setCellValueByColumnAndRow(
                    3,
                    $i,
                    $item->status
                );
                $i++;
            }
            $link = _ROOT . '/file/xls/' . uniqid('', true) . '_' . date('d.m.Y') . '.xlsx';

            $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
            $objWriter->save($link);
            if (file_exists($link)) {
                return $link;
            } else {
                $this->log->error('Файл не создался');
                //$this->msg->error('Файл не создался');

            }

        } catch (Exception $e) {
            $this->log->error('Message: ' . $e->getMessage() . ', File: ' . $e->getFile() . ', Line: ' . $e->getLine());

        }


    }

    /**
     * @param $urls Find emails from array links of pages
     * @return array Array emails from the links
     */
    protected function checkEmail($url)
    {
        $results = array();
        $status = 0;
        $j = 0;

        //foreach ($urls as $url) {

             $url = urldecode($url);
            //foreach ($this->contactUrl as $item) {
            if (is_array($this->input->post('type'))) {
                $t = $url[1];
                $url = $url[0];
                $url = trim($url);
            } else {
                $url = trim($url);
                $url = parse_url($url);

                if(isset($url['scheme'])){
                    $url = $url['scheme'] . '://' . $url['host'];
                }else{
                    $url = 'http://' . $url['path'];
                }

            }



            $curl = new Curl();
            $curl->get(trim($url));
            $contentType = explode('=', $curl->responseHeaders['Content-Type']);


            if ($curl->response != '' && $curl->errorCode >= 500) {

                $this->log->error('Error: url (' . trim($url) . ') ' . $curl->errorCode . ': ' . $curl->errorMessage);
                $results = array(
                    'url' => urlencode($url),
                    'title' => '',
                    'email' => 'Страница не открылась или открылась с ошибкой',
                    'status' => 0,
                    'message'=>' <i class="large material-icons circle">error</i><p class="title" style="margin-top:10px;">Error: url (' . urlencode(trim($url)) . ') Код ошибки от браузера: ' . $curl->errorCode .'</p>'
                );


                //$this->msg->info('По ссылке '.trim($url) .' нет email.');
            } else {
                $emails = PhpMailExtractor::extract($curl->response);

                if (!$emails) {


                    $posibleEmail = $this->findPosibleLinks($curl->response, $url);

                    if (count($posibleEmail) > 0 && $posibleEmail)
                        if (count($emails) > 0 && $emails)
                            $emails = array_merge($emails, $posibleEmail);
                        else
                            $emails = $posibleEmail;

                }


                if (count($emails) > 0) {
                    foreach ($emails as $email) {
                        /*if ($this->input->post('type') == 'request') {
                            $title = $t;
                        } else {*/
                        if (isset($contentType[1])) {
                            if ($contentType[1] == 'UTF-8' || $contentType[1] == '')
                                $title = PhpMailExtractor::pageTitle($curl->response);
                            else
                                $title = iconv($contentType[1], 'UTF-8', PhpMailExtractor::pageTitle($curl->response));
                        } else {
                            $title = PhpMailExtractor::pageTitle($curl->response);
                        }

                        //}
                        $results = array(
                            'url' => urlencode($url),
                            'title' => urlencode($title),
                            'email' => $email,
                            'status' => 1,
                            'message'=> '<i class="large material-icons circle">thumb_up</i><p class="title" style="margin-top:10px;">По ссылке '.urlencode(trim($url)).'  email есть - '.$email.'</p>'
                        );
                        //$this->msg->info('По ссылке '.trim($url).'  email есть - '.$email);
                    }
                    $status = 1;

                } else {

                    $results = array(
                        'url' => urlencode($url),
                        'title' => '',
                        'email' => 'Нет email',
                        'status' => 0,
                        'message'=>'<i class="large material-icons circle">thumb_down</i><p class="title" style="margin-top:10px;">По ссылке '.urlencode(trim($url)) .' нет email</p>'
                    );

                    //$this->msg->info('По ссылке '.trim($url) .' нет email');
                }
            }
            $curl->close();


            //}


        //}

        return $results;

    }

    private function findPosibleLinks($response, $url)
    {

        preg_match_all('#<a(.+?)</a>#si', $response, $links);

        if (is_array($links) && count($links) > 0) {
            $arrPosinleEmails = array();
            foreach ($links[1] as $link) {

                if ($link) {
                    $count = count($this->inTetxContactUrl);
                    for ($i = 0; $i < $count; $i++) {
                        if (strstr($link, $this->inTetxContactUrl[$i])) {
                            $arrPosinleEmails[] = $link;
                        }
                    }
                }
            }

        }

        $checkUrl = array();
        if (!empty($arrPosinleEmails)) {

            foreach ($arrPosinleEmails as $e) {
                preg_match_all('/href=\"(.*)\"/U', $e, $matches);


                if (empty($matches[0])) {
                    preg_match_all("/href=\\'(.*)\\'/U", $e, $matches);

                    if (!empty($matches[1])) {
                        $checkUrl[] = $matches[1][0];
                    }

                } else {
                    $checkUrl[] = $matches[1][0];
                }

            }

        }


        if (!empty($checkUrl[0])) {

            $link = $checkUrl[0];

            if (!strstr($checkUrl[0], 'http://')) {
                if (substr($url, -1) == '/') {
                    $link = $url . substr($checkUrl[0], 1);
                } else {
                    $link = $url . $checkUrl[0];
                }

            }


            $curl = new Curl();
            $curl->get($link);

            if ($curl->response != '' && $curl->errorCode >= 500) {
                $curl->close();
                return false;
            } else {
                preg_match_all('/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $curl->response, $matches);


                $emails = PhpMailExtractor::extract($curl->response);
                $curl->close();

                return $emails;

            }

        }
        return false;
    }

    public function validateJson($status){

        switch ($status) {
            case JSON_ERROR_NONE:
                echo ' - No errors';
                break;
            case JSON_ERROR_DEPTH:
                echo ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                echo ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                echo ' - Unknown error';
                break;
        }

    }
}