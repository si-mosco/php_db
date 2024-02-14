<?php
require "libreria.php"; // per funzioni che verranno eseguite dal server e che possono servire 
require "credenziali.php"; //per tenere le credenziali di connessione al database

session_start();

$name = $_POST['nome']; 
$pass = $_POST['password']; 
$verifica = $_POST['verifica_password'];

// Controllo se esiste già un utente con lo stesso nome nel database
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT username FROM credenziali WHERE username = :name";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $utentiDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($utentiDB) > 0) { // se esite +0 utenti con quel nome lancio errore
        echo "Nome utente già presente nel database";
        
    } else {
        if ($pass == $verifica) {
            $hashedPassword = hash('sha256', $pass);

            // Query per inserire utente
            $sql = "INSERT INTO credenziali (username, password) VALUES (:name, :pass)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':pass', $hashedPassword);
            $stmt->execute();
            
            echo "Registrazione avvenuta con successo!";
            header("location: modulo.html"); // reindirizzamento alla pagina di login 
        } else {
            echo "Errore durante la registrazione, le password non coincidono";
        }
    }
} catch (PDOException $e) {
    echo "Errore durante la registrazione: " . $e->getMessage();
}
?>
