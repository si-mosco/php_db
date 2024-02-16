<?php
session_start();
require "libreria.php";
require "credenziali.php";

echo "<html>
        <head>
        <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            margin-bottom: 70px;
            background-color: #222;
            color: #ddd;
        }
        h1 {
            text-align: center;
            color: #4B0082; /* Indaco */
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background-color: #333;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            color: #ddd;
        }

        th {
            background-color: #4B0082; /* Indaco */
            color: #ddd;
        }

        tr:nth-child(even) {
            background-color: #444;
        }
        footer {
            background-color: #111;
            color: #ddd;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        button {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #4B0082; /* Indaco */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #6A5ACD; /* Lavanda scuro */
        }
        form {
            margin-top: 20px;
            text-align: center;
        }
        label {
            font-weight: bold;
            margin-right: 10px;
            color: #ddd;
        }
        select, input {
            padding: 8px;
            margin-right: 10px;
        }
    </style>
        </head>
        <body>";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['nome']) && !empty($_POST['cognome']) && !empty($_POST['email']) && !empty($_POST['id_luogo'])) {
            $sqlInsert = "INSERT INTO clienti (nome, cognome, email, id_luogo) VALUES (:nome, :cognome, :email, :id_luogo)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bindParam(':nome', $_POST['nome']);
            $stmt->bindParam(':cognome', $_POST['cognome']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':id_luogo', $_POST['id_luogo']);
            $stmt->execute();
            echo "<p>Nuovo cliente aggiunto con successo!</p>";
            // Reindirizza alla pagina principale dopo l'aggiunta
            echo "<script>window.location.href = 'protetta.php';</script>";
        } else {
            echo '<script type="text/javascript">
            window.onload = function () { alert("Tutti i campi sono obbligatori!"); } 
            </script>'; 
        }
    }

    $sqlLuoghi = "SELECT * FROM luoghi_consegna";
    $statementLuoghi = $conn->query($sqlLuoghi);
    $luoghi = $statementLuoghi->fetchAll(PDO::FETCH_ASSOC);

    echo "<div>
            <h1>Aggiungi Cliente</h1>
            <form method='POST'>
                <label for='nome'>Nome:</label>
                <input type='text' name='nome'><br><br>

                <label for='cognome'>Cognome:</label>
                <input type='text' name='cognome'><br><br>

                <label for='email'>Email:</label>
                <input type='email' name='email'><br><br>

                <label for='id_luogo'>Luogo di Consegna:</label>
                <select name='id_luogo'>";
                foreach ($luoghi as $luogo) {
                    echo "<option value='{$luogo['id']}'>{$luogo['citta']}, {$luogo['nazione']}</option>";
                }
    echo "</select><br><br>

                <button type='submit'>Aggiungi Cliente</button>
                </form>
                
                <button onclick='goToHomePage()'>Torna alla pagina principale</button>
            </div>";
        
    // JavaScript per reindirizzare alla pagina principale
    echo "<script>
            function goToHomePage() {
            window.location.href = 'protetta.php';
        }
        </script>";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} finally {
    $conn = null;
}

echo "</body>
    </html>";
?>
