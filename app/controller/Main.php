<?php

/**
 * Class Main
 */
class Main extends Parser
{
    /**
     * @var Request
     */
    public $input;

    /**
     * Main constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->input = new Request();

        if ($this->input->get('type') == 'in_work') {
            $this->readFindsmails();
        }

    }

    /**
     * @param null $time
     */
    public function readFindsmails($time = NULL)
    {

        $time = $this->input->get('time');

        $results = $this->db->query('SELECT * FROM email WHERE time >="' . $time . '" AND user_id!="' . $this->user_id . '" ');
        $data = array();

        while ($row = $results->fetchArray(SQLITE3_NUM)) {
            if ($row[0] != '') {
                $data[] = $row;
            }

        }

        header("Content-type: application/json; charset=utf-8");
        header("Cache-Control: must-revalidate");
        header("Pragma: no-cache");
        header("Expires: -1");
        $json = json_encode($data);
        if ($json) {
            print $json;
        } else {
            $this->validateJson(json_last_error());
        }
        exit();

        //$this->db->exec('DELETE FROM email');

    }

    /**
     * Main page
     *
     */
    public function index()
    {

        if ($this->input->post('type')) {
            $type = $this->input->post('type');

            if ($type == 'checkEmail') {

                $item = json_decode($this->input->post('item'));
                $result = $this->checkEmail($item);
                header("Content-type: application/json; charset=utf-8");
                header("Cache-Control: must-revalidate");
                header("Pragma: no-cache");
                header("Expires: -1");
                $json = json_encode($result);
                if ($json) {
                    print $json;
                } else {

                    $this->validateJson(json_last_error());
                }
                exit();

            }

            if ($type == 'writeEmail') {

                $items = json_decode($this->input->post('items'));

                $result = $this->createExcel($items);

                if ($result) {
                    //$message = '<p>Файл успешно создан</p>';
                    //$this->msg->success('Файл успешно создан.');
                    $message = '  <i class="large material-icons circle">email</i><p class="title" style="margin-top:10px;">' . $this->sendEmail($this->input->post('email'), $result) . '</p>';

                } else {
                    //$this->msg->error('Ошибка при создании файла');
                    //$this->log->error('Ошибка при создании файла');
                    $message = '<i class="large material-icons circle">error</i><p class="title" style="margin-top:10px;">Ошибка при создании файла</p>';
                }

                header("Content-type: application/json; charset=utf-8");
                header("Cache-Control: must-revalidate");
                header("Pragma: no-cache");
                header("Expires: -1");
                $json = json_encode(array('message' => $message));
                if ($json) {
                    print $json;
                } else {
                    $this->validateJson(json_last_error());
                }
                exit();
            }

            if (!$this->input->post('text')) {
                $message = 'Вы не ввели данные в форму';
                //$this->msg->error('Вы не ввели данные в форму');
            } else {

                if ($this->input->post('type') == 'request') {
                    $this->setOutput(new urlFind($this->log, $this->msg, $this->input->post('text'), $this->input->post('count')));
                } else {
                    $this->setOutput(new fileFind($this->input->post('text')));
                }
                $this->loadOutput();


            }
        }

        include_once(_ROOT . '/app/view/index.php');


    }

    /**
     * @param $email
     * @param $link
     * @return string
     * @throws phpmailerException
     */
    public function sendEmail($email, $link)
    {
        $mail = new PHPMailer;

        //TODO change emails to right
        $mail->setFrom('seattle28@yandex.ru', 'Письмо с сайта парсинга email');
        $mail->CharSet = "UTF-8";
        //$mail->addAddress($email, 'User');
        $mail->addAddress($email);

        $mail->addReplyTo('seattle28@yandex.ru');
        // TODO chamge CC and BB
        //$mail->addCC('info@native.cli.bz');
        //$mail->addBCC('info@native.cli.bz');

        $mail->addAttachment($link);
        $mail->isHTML(true);

        //TODO chamge sublect
        $mail->Subject = 'Письмо с сайта парсинга email';

        //TODO change email body
        $mail->Body = 'This is the HTML message body <b>in bold!</b>';

        try {

            if (!$mail->send()) {
                $this->log->error(' Ошибка отправки сообщения: ' . $mail->ErrorInfo);
                //$this->msg->error(' Ошибка отправки сообщения: ' . $mail->ErrorInfo);
                return ' Ошибка отправки сообщения: ' . $mail->ErrorInfo;
            } else {
                //$this->msg->success('Запрос принят, ждите на почте');
                return 'Сообщение успешно отправлено';
            }
        } catch (Exception $e) {
            $this->log->error('Message: ' . $e->getMessage() . ', File: ' . $e->getFile() . ', Line: ' . $e->getLine());
            //$this->msg->error(' Ошибка отправки сообщения: ' . $e->getMessage());
            return ' Ошибка отправки сообщения: ' . $e->getMessage();
        }

    }

}