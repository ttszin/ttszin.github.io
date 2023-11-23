<?php


    // If necessary, modify the path in the require statement below to refer to the 
    // location of your Composer autoload.php file.
    require 'vendor/autoload.php';
    use Aws\Ses\SesClient;
    use Aws\Exception\AwsException;
    // Create an SesClient. Change the value of the region parameter if you're 
    // using an AWS Region other than US West (Oregon). Change the value of the
    // profile parameter if you want to use a profile in your credentials file
    // other than the default.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $SesClient = new SesClient([
        'profile' => 'Matheus',
        'version' => '2010-12-01',
        'region'  => 'sa-east-1'
    ]);

    // Replace sender@example.com with your "From" address.
    // This address must be verified with Amazon SES.
    $sender_email = 'matheuspcgames1@gmail.com';

    // Replace these sample addresses with the addresses of your recipients. If
    // your account is still in the sandbox, these addresses must be verified.
    $recipient_emails = ['matheuskrskr@gmail.com'];

    // Specify a configuration set. If you do not want to use a configuration
    // set, comment the following variable, and the
    // 'ConfigurationSetName' => $configuration_set argument below.
    $configuration_set = 'ConfigSet';

    $nome = addslashes($_POST['nome']);
    $email = addslashes($_POST['email']);
    $telefone = addslashes($_POST['telefone']);
    $mensagem = addslashes($_POST['mensagem']);

    $subject = 'Mensagem do site Portfólio';
    $plaintext_body = 'Alguém enviou uma mensagem no portfólio !' ;
    $char_set = 'UTF-8';

    $body = "Nome: ".$nome."\n"
      ."Telefone: ".$telefone."\n"
      ."Email: ".$email."\n"
      ."Mensagem: ".$mensagem;

    // Usa-se nl2br para converter \n em <br> para quebras de linha em HTML
    $htmlBody = nl2br($body);
    try {
        $result = $SesClient->sendEmail([
            'Destination' => [
                'ToAddresses' => $recipient_emails,
            ],
            'ReplyToAddresses' => [$email],
            'Source' => $sender_email,
            'Message' => [
            'Body' => [
                'Html' => [
                    'Charset' => $char_set,
                    'Data' => $htmlBody,
                ],
                'Text' => [
                    'Charset' => $char_set,
                    'Data' => $plaintext_body,
                ],
            ],
            'Subject' => [
                'Charset' => $char_set,
                'Data' => $subject,
            ],    
            ],
            // If you aren't using a configuration set, comment or delete the
            // following line
            'ConfigurationSetName' => $configuration_set,
        ]);
        $messageId = $result['MessageId'];
        echo("A mensagem foi enviada com sucesso, aguarde o retorno !!");
    } catch (AwsException $e) {
        // output error message if fails
        echo $e->getMessage();
        echo("Erro ao enviar o email ! ".$e->getAwsErrorMessage()."\n");
        echo "\n";
    }

    }
?>
