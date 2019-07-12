<?php  class Mail  {    /**    * @var string $mensajeDeError     * Variable en la que se almacena el mensaje de Error    *    * @acces private    */    var $mensajeDeError;          /**    * @var array $destinatarios     * Variable en la que se almacenan los usuarios a los que se les enviara el correo    *    * @acces private    */    var $destinatarios;           /**    * @var array $adjuntos     * Variable en la que se almacenan las rutas de los archivos adjuntos    *    * @acces private    */    var $adjuntos;        /**    * @var string $asunto    * Variable en la que se almacena el asunto del mail    *    * @acces private    */    var $asunto;        /**    * @var string $mensaje    * Variable en la que se almacena el cuerpo del mail    *    * @acces private    */    var $mensaje;    /**    * Constructor de la clase    */    function Mail(){}    /**    * Funcion donde se obtiene la confiduracion del servidor de correo    *    * @return mixed    */    function ObtenerConfiguracion()    {      $config = array();      $archivo = "C:/Apache/www/SIIS/classes/Mail/config_mail.ini";      if(file_exists($archivo))      {        $config = parse_ini_file($archivo,true);        return $config;      }      echo "ARCHIVO NO EXISTE ".$archivo;      return false;    }    /**    * Funcion donde se ingresan los destinatarios del correo a enviar    *    * @param string $direccion Direccion de correo a la que se enviara el correo    * @param string $nombre Nombre de la persona a la que se enviara el correo    *    */    function SetDestinatarios($direccion,$nombre)    {      $this->destinatarios[$direccion] = $nombre;    }    /**    * Funcion donde se ingresa el remitente    *    * @param string $direccion Direccion de correo del remitente    * @param string $nombre Nombre del remitente    *    */    function SetRemitente($direccion,$nombre)    {      $this->remitente_direccion = $direccion;      $this->remitente_nombre = $nombre;    }          /**    * Funcion donde se ingresa el asunto    *    * @param string $asunto Asunto del mail    *    */    function SetAsunto($asunto)    {      $this->asunto = $asunto;    }        /**    * Funcion donde se ingresa el asunto    *    * @param string $mensaje Mensaje del mail    *    */    function SetMensaje($mensaje)    {      $this->mensaje = $mensaje;    }    /**    * Funcion donde se ingresa la ruta del adjunto    *    * @param string $ruta_adjunto Ruta del archivo adjunto    *    */    function SetAdjunto($ruta_adjunto)    {      $this->adjuntos[] = $ruta_adjunto;    }    /**    * Funcion donde se hace el envio del mail    *    * @return boolean    */    function EnviarMail()    {      include "phpmailer/class.phpmailer.php";            $mail = new PHPMailer();            $datos = $this->ObtenerConfiguracion();      if (empty($datos) || $datos === false)      {        $this->mensajeDeError = "LOS DATOS DE LA CONFIGURACION DEL SERVIDOR DE CORREO ESTA VACIA O ES INCORRECTA";        return false;      }      $mail = new PHPMailer();      $mail->IsSMTP();      $mail->SMTPAuth   = $datos['mail']['smtp_autenticacion']; // enable SMTP authentication      $mail->SMTPDebug  = 2;      if($datos['mail']['secure'])              $mail->SMTPSecure = $datos['mail']['secure'];           // sets the prefix to the servier            $mail->Host       = $datos['mail']['host'];                // sets GMAIL as the SMTP server            if(!empty($datos['mail']['port']))        $mail->Port       = $datos['mail']['port'];            $mail->Username   = $datos['mail']['smtp_user'];// SMTP username      $mail->Password   = $datos['mail']['smtp_pass'];// SMTP password            $mail->From = $this->remitente_direccion;      $mail->FromName = $this->remitente_nombre;            foreach($this->destinatarios as $key => $name)        $mail->AddAddress($key, $name);            $mail->WordWrap = 300;               // set word wrap to 50 characters      if(!empty($this->adjuntos))      {        foreach($this->adjuntos as $k1 => $archivo)          $mail->AddAttachment($archivo); // add attachments      }      $mail->IsHTML(true);                // set email format to HTML      $mail->Subject = $this->asunto;      $mail->Body    = $this->mensaje;      $mail->AltBody = strip_tags($this->mensaje);      if(!$mail->Send())      {        $this->mensajeDeError = "EL MAIL NO HA PODIDO SER ENVIADO<BR> ERROR: ".$mail->ErrorInfo;        return false;      }      return true;    }  }?>