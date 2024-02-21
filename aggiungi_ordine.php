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
        if (!empty($_POST['cliente_id']) && !empty($_POST['data_ordine']) && !empty($_POST['oggetti'])) {
            $cliente_id = $_POST['cliente_id'];
            $data_ordine = $_POST['data_ordine'];

            $sqlInsert = "INSERT INTO ordini (cliente_id, data_ordine) VALUES (:cliente_id, :data_ordine)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->bindParam(':data_ordine', $data_ordine);
            $stmt->execute();
            $order_id = $conn->lastInsertId();

            foreach ($oggetti as $oggetto_id) {
                $sqlInsertOrdiniOggetti = "INSERT INTO ordini_oggetti (id_ordini, id_oggetti) VALUES (:id_ordini, :id_oggetti)";
                $stmt = $conn->prepare($sqlInsertOrdiniOggetti);
                $stmt->bindParam(':id_ordini', $order_id);
                $stmt->bindParam(':id_oggetti', $oggetto_id);
                $stmt->execute();
            }

            echo "<p>Nuovo ordine aggiunto con successo!</p>";
            // Reindirizza alla pagina principale dopo l'aggiunta
            echo "<script>window.location.href = 'ordini.php';</script>";
        } else if (!empty($_POST['id_elimina'])) {
            $id_elimina = $_POST['id_elimina'];

            $sqlDelete = "DELETE FROM ordini WHERE id = :id";
            $stmt = $conn->prepare($sqlDelete);
            $stmt->bindParam(':id', $id_elimina);
            $stmt->execute();

            echo "<p>Ordine eliminato con successo!</p>";
        } else {
            echo '<script type="text/javascript">
            window.onload = function () { alert("Tutti i campi sono obbligatori!"); } 
            </script>'; 
        }
    }

    $sqlClienti = "SELECT * FROM clienti";
    $statementClienti = $conn->query($sqlClienti);
    $clienti = $statementClienti->fetchAll(PDO::FETCH_ASSOC);

    $sqlOggetti = "SELECT * FROM oggetti";
    $statementOggetti = $conn->query($sqlOggetti);
    $oggetti = $statementOggetti->fetchAll(PDO::FETCH_ASSOC);

    echo "<div>
        <h1>Aggiungi Ordine</h1>
        <form method='POST'>
            <label for='cliente_id'>Cliente:</label>
            <select name='cliente_id'>";
            foreach ($clienti as $cliente) {
                echo "<option value='{$cliente['id']}'>{$cliente['nome']} {$cliente['cognome']}</option>";
            }
echo "</select><br><br>

            <label for='data_ordine'>Data Ordine:</label>
            <input type='date' name='data_ordine'><br><br>

            <button type='submit'>Aggiungi Ordine</button>
        </form>
    </div>";

    echo "<div>
            <h1>Elimina Ordine</h1>
            <form method='POST'>
                <label for='id_elimina'>Seleziona ID dell'ordine da eliminare:</label>
                <select name='id_elimina' id='id_elimina'>";
    $sqlSelect = "SELECT id, data_ordine FROM ordini";
    $stmt = $conn->query($sqlSelect);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['data_ordine'] . "</option>";
    }
    echo "</select><br><br>
                <button type='submit'>Elimina Ordine</button>
            </form>
        </div>";

    // JavaScript per reindirizzare alla pagina principale
    echo "<script>
            function goToHomePage() {
                window.location.href = 'ordini.php';
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