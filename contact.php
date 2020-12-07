<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

// activation du système d'autoloading de Composer
require __DIR__.'/vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__.'/templates');

// instanciation du moteur de template
$twig = new Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
]);
// chargement de l'extension DebugExtension
$twig->addExtension(new DebugExtension());

// permet d'afficher le contenu de la variable
$errors = [];

if ($_POST) {
    // validation des emails
    $emailMaxlenght = 190;
    if (empty($_POST['email'])) {
        $errors['email'] = 'Merci de renseigner votre email';
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = "Cet email n'est pas valide, merci de renseigner un email valide";
    } elseif (strlen($_POST['email']) >= $emailMaxlenght) {
        // le message ne dois pas dépasser 190 caractères
        $errors['email'] = "Votre email doit faire au maximum {$emailMaxlenght} caractères inclus";
    }

    $minLenght = 3;
    // validation du sujet
    $subjectMaxlenght = 190;
    if (empty($_POST['subject'])) {
        $errors['subject'] = "Merci de renseigner l'objet de votre demande de contact";
    } elseif (strlen($_POST['subject']) < $minLenght || strlen($_POST['subject']) >= $subjectMaxlenght) {
        // le message dois être compris entre 3 et 190 caractères inclus
        $errors['subject'] = "Votre objet doit faire au minimum {$minLenght} et au maximum {$subjectMaxlenght} caractères inclus";
    } elseif (preg_match('/<[^>]*>/', $_POST['subject']) === 1) {
        // pas de code HTML dans le formulaire
        $errors['subject'] = "Vous ne pouvez pas entrer de code HTML dans votre message";
    }
    
    // validation du message
    $textareaMaxlenght = 1000;
    if (empty($_POST['message'])) {
        $errors['message'] = "Merci de renseigner votre message";
    } elseif (strlen($_POST['message']) < $minLenght || strlen($_POST['message']) >= $textareaMaxlenght) {
        // le message ne dois pas dépasser 1000 caractères
        $errors['message'] = "Votre message doit faire au minimum {$minLenght} et au maximum {$textareaMaxlenght} caractères inclus";
    } elseif (preg_match('/<[^>]*>/', $_POST['message']) === 1) {
        // pas de code HTML dans le formulaire
        $errors['message'] = "Vous ne pouvez pas entrer de code HTML dans votre message";
    }
}




// affichage du rendu d'un template
echo $twig->render('contact.html.twig', [
    // transmission de données au template
    'errors' => $errors,
]);