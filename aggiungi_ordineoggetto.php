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
    margin: 20px auto;
    width: 500px;
    padding: 20px;
    text-align: center;
    border-radius: 5px;
    background-color: #333;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

            echo "<script type='text/javascript'>alert('Ordine oggetto aggiunto con successo!');</script>";
            echo "<script>window.location.href = 'oggetti_ordini.php';</script>";
        } else if (!empty($_POST['id_elimina'])) {
            $id_elimina = $_POST['id_elimina'];

            $sqlDelete = "DELETE FROM ordini_oggetti WHERE id = :id";
            $stmt = $conn->prepare($sqlDelete);
            $stmt->bindParam(':id', $id_elimina);
            $stmt->execute();

            echo "<script type='text/javascript'>alert('Ordine oggetto eliminato con successo!');</script>";
            echo "<script>window.location.href = 'oggetti_ordini.php';</script>";
        } else if (!empty($_POST['id_modifica'])) {
            $id_modifica = $_POST['id_modifica'];
            $id_ordine_modificato = $_POST['id_ordine_modifica'];
            $id_oggetti_modificato = $_POST['id_oggetti_modifica'];

            $sqlUpdate = "UPDATE ordini_oggetti SET id_ordini = :id_ordine, id_oggetti = :id_oggetti WHERE id = :id";
            $stmt = $conn->prepare($sqlUpdate);
            $stmt->bindParam(':id_ordine', $id_ordine_modificato);
            $stmt->bindParam(':id_oggetti', $id_oggetti_modificato);
            $stmt->bindParam(':id', $id_modifica);
            $stmt->execute();

            echo "<script type='text/javascript'>alert('Ordine oggetto modificato con successo!');</script>";
            echo "<script>window.location.href = 'oggetti_ordini.php';</script>";
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
            <h1>Elimina</h1>
            <form method='POST'>
                <label for='id_elimina'>Seleziona ID dell'ordine oggetto da eliminare:</label>
                <select name='id_elimina' id='id_elimina'>";
    $sqlSelect = "SELECT id, id_ordini, id_oggetti FROM ordini_oggetti";
    $stmt = $conn->query($sqlSelect);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - ID Ordine: " . $row['id_ordini'] . ", ID Oggetto: " . $row['id_oggetti'] . "</option>";
    }
    echo "</select><br><br>
                <button type='submit' name='submit_elimina'>Elimina Ordine Oggetto</button>
            </form>
            
            <h1>Modifica</h1>
            <form method='POST'>
                <label for='id_modifica'>Seleziona ID dell'ordine oggetto da modificare:</label>
                <select name='id_modifica' id='id_modifica'>";
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - ID Ordine: " . $row['id_ordini'] . ", ID Oggetto: " . $row['id_oggetti'] . "</option>";
    }
    echo "</select><br><br>";

    echo "<label for='id_ordine_modifica'>Nuovo ID Ordine:</label>
                <select name='id_ordine_modifica'>";
    foreach ($ordini as $ordine) {
        echo "<option value='{$ordine['id']}'>{$ordine['id']}</option>";
    }
    echo "</select><br><br>";

    echo "<label for='id_oggetti_modifica'>Nuovo ID Oggetto:</label>
                <select name='id_oggetti_modifica'>";
    foreach ($oggetti as $oggetto) {
        echo "<option value='{$oggetto['id']}'>{$oggetto['nome']}</option>";
    }
    echo "</select><br><br>";

    echo "<button type='submit' name='submit_modifica'>Modifica Ordine Oggetto</button>
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
