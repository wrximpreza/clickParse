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
    }

    /**
     * Main page
     *
     */
    public function index()
    {

        $client = new Parser();

        if ($this->input->post('type')) {
            $type = $this->input->post('type');

            if($type == 'checkEmail'){

                $result = $client->checkEmail($this->input->post('item'));
                header("Content-type: application/json; charset=utf-8");
                header("Cache-Control: must-revalidate");
                header("Pragma: no-cache");
                header("Expires: -1");
                $json = json_encode($result);
                print $json;
                exit();

            }
            
            if($type == 'writeEmail'){

                $items = json_decode($this->input->post('items'));

                $result = $client->createExcel($items);

                if ($result) {

                    $message = '<p>Файл успешно создан</p>';
                    //$this->msg->success('Файл успешно создан.');
                    $message = $message . $this->sendEmail($this->input->post('email'), $result);
                    echo $message;

                } else {
                    //$this->msg->error('Ошибка при создании файла');
                    $this->log->error('Ошибка при создании файла');
                    echo 'Ошибка при создании файла';
                }
                exit();
            }

            

            if (!$this->input->post('text')) {
                $message = 'Вы не ввели данные в форму';
                $this->msg->error('Вы не ввели данные в форму');
            } else {

                if ($this->input->post('type') == 'request') {
                    $client->setOutput(new urlFind($this->log, $this->msg, $this->input->post('text'), $this->input->post('count')));
                } else {
                    $client->setOutput(new fileFind($this->input->post('text')));
                }
                $client->loadOutput();


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
                $this->msg->error(' Ошибка отправки сообщения: ' . $mail->ErrorInfo);

                return ' Ошибка отправки сообщения: ' . $mail->ErrorInfo;
            } else {
                $this->msg->success('Запрос принят, ждите на почте');
                /*echo '<pre>';
                print_r($mail);
                echo '</pre>';
                exit();*/
                return 'Сообщение успешно отправлено';
            }
        } catch (Exception $e) {
            $this->log->error('Message: '. $e->getMessage(). ', File: '. $e->getFile(). ', Line: '.$e->getLine());
            $this->msg->error(' Ошибка отправки сообщения: ' . $e->getMessage());

            return ' Ошибка отправки сообщения: ' . $e->getMessage();
        }

    }

}