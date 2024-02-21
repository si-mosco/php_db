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

    echo "<footer>
        <button onclick='goToHomePage()'>Torna alla pagina principale</button>
      </footer><br>";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['nome']) && !empty($_POST['costo'])) {
            $nome = $_POST['nome'];
            $costo = $_POST['costo'];

            $sqlInsert = "INSERT INTO oggetti (nome, costo) VALUES (:nome, :costo)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':costo', $costo);
            $stmt->execute();

            echo "<p>Nuovo oggetto aggiunto con successo!</p>";
        } else if (!empty($_POST['id_elimina'])) {
            $id_elimina = $_POST['id_elimina'];

            $sqlDelete = "DELETE FROM oggetti WHERE id = :id";
            $stmt = $conn->prepare($sqlDelete);
            $stmt->bindParam(':id', $id_elimina);
            $stmt->execute();

            echo "<p>Oggetto eliminato con successo!</p>";
        } else {
            echo '<script type="text/javascript">
            window.onload = function () { alert("Tutti i campi sono obbligatori!"); } 
            </script>'; 
        }
    }

    echo "<div>
            <h1>Aggiungi Oggetto</h1>
            <form method='POST'>
                <label for='nome'>Nome:</label>
                <input type='text' name='nome'><br><br>

                <label for='costo'>Costo:</label>
                <input type='number' name='costo' min='0'><br><br>

                <button type='submit'>Aggiungi Oggetto</button>
            </form>
        </div>";

    echo "<div>
            <h1>Elimina Oggetto</h1>
            <form method='POST'>
                <label for='id_elimina'>Seleziona ID dell'oggetto da eliminare:</label>
                <select name='id_elimina' id='id_elimina'>";
    $sqlSelect = "SELECT id, nome FROM oggetti";
    $stmt = $conn->query($sqlSelect);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['nome'] . "</option>";
    }
    echo "</select><br><br>
                <button type='submit'>Elimina Oggetto</button>
            </form>
        </div>";

    // JavaScript per reindirizzare alla pagina principale
    echo "<script>
            function goToHomePage() {
                window.location.href = 'oggetti.php';
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
