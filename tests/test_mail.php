<?php

// Incluez le fichier d'autoload de Composer.
require __DIR__.'/../vendor/autoload.php'; // Ajustez ce chemin en fonction de la structure de votre projet.

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Utilisez le DSN configuré dans votre fichier .env ou .env.local.
$dsn = 'smtp://syseva2024@gmail.com:hueggakukjuecntz@smtp.gmail.com';
$transport = Transport::fromDsn($dsn);

$mailer = new Mailer($transport);

$email = (new Email())
    ->from('syseva2024@gmail.com') // Adresse e-mail de l'expéditeur.
    ->to('ahmedkoubaa87@gmail.com') // Adresse e-mail du destinataire pour tester.
    ->subject('Test Email from Symfony')
    ->text('Sending emails is fun again!');

$mailer->send($email);

echo "Email sent successfully!";
?>