<?php

class email
{
    public function send($_to, $_subject, $_message)
    {
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        //        $headers .= 'From: <webmaster@example.com>' . "\r\n";
        //        $headers .= 'Cc: myboss@example.com' . "\r\n";

        $result = mail($_to, $_subject, $_message, $headers);
        if (!$result) {
            return false;
        }
        return true;
    }

    public function generateConfirmationCode($_userEmail)
    {
        return md5(uniqid($_userEmail, true));
    }
}
