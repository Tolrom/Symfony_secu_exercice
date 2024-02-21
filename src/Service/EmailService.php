<?php
namespace App\Service;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private string $smtp_server;
    private string $smtp_account;
    private string $smtp_password;
    private int $smtp_port;

    public function __construct(string $smtp_server, string $smtp_account, string $smtp_password, int $smtp_port){
        $this->smtp_server = $smtp_server;
        $this->smtp_account = $smtp_account;
        $this->smtp_password = $smtp_password;
        $this->smtp_port = $smtp_port;
    }
    public function sendEmail(string $receiver, string $subject,string $content): string{
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $this->smtp_server;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $this->smtp_account;                     //SMTP username
            $mail->Password   = $this->smtp_password;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = $this->smtp_port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($this->smtp_account, 'Symfotest');
            $mail->addAddress($receiver);

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $content;

            $mail->send();
            return 'Message has been sent';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function testConfig(){
        return "Config : ".$this->smtp_server." : ".$this->smtp_account;
    }
}