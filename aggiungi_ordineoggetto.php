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
        if (!empty($_POST['id_ordine']) && !empty($_POST['id_oggetti'])) {
            $sqlInsert = "INSERT INTO ordini_oggetti (id_ordini, id_oggetti) VALUES (:id_ordine, :id_oggetti)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bindParam(':id_ordine', $_POST['id_ordine']);
            $stmt->bindParam(':id_oggetti', $_POST['id_oggetti']);
            $stmt->execute();
            echo "<p>Ordine oggetto aggiunto con successo!</p>";
            // Reindirizza alla pagina principale dopo l'aggiunta
            echo "<script>window.location.href = 'oggetti_ordini.php';</script>";
        } else if (!empty($_POST['id_elimina'])) {
            $id_elimina = $_POST['id_elimina'];

            $sqlDelete = "DELETE FROM ordini_oggetti WHERE id = :id";
            $stmt = $conn->prepare($sqlDelete);
            $stmt->bindParam(':id', $id_elimina);
            $stmt->execute();

            echo "<p>Ordine oggetto eliminato con successo!</p>";
        } else {
            echo '<script type="text/javascript">
            window.onload = function () { alert("Tutti i campi sono obbligatori!"); } 
            </script>'; 
        }
    }

    $sqlOrdini = "SELECT * FROM ordini";
    $statementOrdini = $conn->query($sqlOrdini);
    $ordini = $statementOrdini->fetchAll(PDO::FETCH_ASSOC);

    $sqlOggetti = "SELECT * FROM oggetti";
    $statementOggetti = $conn->query($sqlOggetti);
    $oggetti = $statementOggetti->fetchAll(PDO::FETCH_ASSOC);

    echo "<div>
            <h1>Aggiungi Oggetto all'Ordine</h1>
            <form method='POST'>
            <label for='id_ordine'>Seleziona ID Ordine:</label>
            <select name='id_ordine'>";
            foreach ($ordini as $ordine) {
                echo "<option value='{$ordine['id']}'>{$ordine['id']}</option>";
            }
echo "</select><br><br>

                <label for='id_oggetti'>Oggetto:</label>
                <select name='id_oggetti'>";
                foreach ($oggetti as $oggetto) {
                    echo "<option value='{$oggetto['id']}'>{$oggetto['nome']}</option>";
                }
    echo "</select><br><br>

                <button type='submit'>Aggiungi Oggetto nell'Ordine</button>
                </form>
                </div>";

    echo "<div>
            <h1>Elimina Ordine Oggetto</h1>
            <form method='POST'>
                <label for='id_elimina'>Seleziona ID dell'ordine oggetto da eliminare:</label>
                <select name='id_elimina' id='id_elimina'>";
    $sqlSelect = "SELECT id, id_ordini, id_oggetti FROM ordini_oggetti";
    $stmt = $conn->query($sqlSelect);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - ID Ordine: " . $row['id_ordini'] . ", ID Oggetto: " . $row['id_oggetti'] . "</option>";
    }
    echo "</select><br><br>
                <button type='submit'>Elimina Ordine Oggetto</button>
            </form>
        </div>";

    // JavaScript per reindirizzare alla pagina principale
    echo "<script>
            function goToHomePage() {
                window.location.href = 'oggetti_ordini.php';
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
