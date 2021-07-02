<?php

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';


        $nombre     =  $_POST['nombre'];
        $correo     =  $_POST['correo'];
        $mensaje    =  $_POST['mensaje'];

        $directorio = "../img/";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0755,true);
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'],$directorio . $_FILES['foto']['name'])) {
            $imagen_url = $_FILES['foto']['name'];
        } else {
            $respuesta = array(
                'respuesta' => error_get_last()
            );
        }

        $mail = new PHPMailer();

        // ionos hosting mail data
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.live.com";
        $mail->Port = 587;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
    
        $mail->Username = 'ray_garcia_a2@live.com'; //Correo de donde enviaremos los correos
        $mail->Password = 'Zombie-Core!22'; // Password de la cuenta de envío
        
        $mail->setFrom('ray_garcia_a2@live.com', 'Ray');
    
        $mail->addAddress($correo);
    
        $message  = "<html><body>";
    
        $message .= "<p>Hola mi nombre es <b>$nombre</b> el motivo de mi mensaje es el siguiente: <br> $mensaje </p>";
    
        
        $message .= "</body></html>";
        
        // HTML email ends here
    
        $mail->addAttachment($directorio . $_FILES['foto']['name'], $imagen_url);
    
        $mail->Subject = "mensaje";
        $mail->Body    = $mensaje;
        $mail->AltBody    = "mensaje";
    
        //send the message, check for errors
        if (!$mail->send()) {
            $respuesta = array(
                'respuesta' => $mail->ErrorInfo
            );
        } else {
            $respuesta = array(
                'respuesta' => 'correcto'
            );
        }

        // codificamos la respuesta en formato JSON para atender con petición AJAX
        die(json_encode($respuesta));
    