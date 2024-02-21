<?php

session_start();
// verifie le nombre de tentatives précedentes ou pas
$loginAttempts = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] : 0;
function bloque_tentatives() {
    // Vérifiez si la variable de session existe
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
        $_SESSION['last_attempt_time'] = time();
    } else {
        // Vérifiez si la dernière tentative était il y a moins d'une minute
        $temps_ecoule = time() - $_SESSION['last_attempt_time'];
        if ($temps_ecoule < 60) {
            // Bloquez l'authentification pour une minute
            echo "deux tentatives ratés en moins d'une minute.";
            exit();
        } else {
            // Réinitialisez le compteur et le temps de la dernière tentative
            $_SESSION['login_attempts'] = 1;
            $_SESSION['last_attempt_time'] = time();
        }
    }
}
bloque_tentatives();
// Définir le nombre maximal de tentatives autorisées
$maxLoginAttempts = 3;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Vérifier les informations d'authentification 
    $correctUsername = 'yed';
    $correctPassword = 'coucou';

    if ($username === $correctUsername && $password === $correctPassword) {
        // Authentification réussie
        $_SESSION['username'] = $username;
        $_SESSION['login_attempts'] = 0; // Réinitialiser le nombre de tentatives après une connexion réussie
        echo "bien joué !!";
        exit();
    } 
    else 
    {
        // Authentification échouée
        $_SESSION['login_attempts'] = $loginAttempts + 1;

        if ($loginAttempts >= $maxLoginAttempts) {
            // Bloquer le formulaire après trois tentatives échouées
            echo 'Trop de tentatives échouées. Veuillez réessayer plus tard.';
            exit();
        } 
        else 
        {
            // Afficher un message d'erreur
            echo 'Nom d\'utilisateur ou mot de passe incorrect. Tentative ' . ($loginAttempts + 1) . ' sur ' . $maxLoginAttempts . '.';
        }
    }
}
