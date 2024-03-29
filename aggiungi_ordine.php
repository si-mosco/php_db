<?php
session_start();
require "libreria.php";
require "credenziali.php";

echo "<html>
        <head>
        <link rel='stylesheet' href='mystyle.css'>
        </head>
        <body>";

echo "<footer>
        <button onclick='goToHomePage()'>Torna alla pagina principale</button>
      </footer><br>";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['cliente_id']) && !empty($_POST['data_ordine'])) {
            $cliente_id = $_POST['cliente_id'];
            $data_ordine = $_POST['data_ordine'];

            $sqlInsert = "INSERT INTO ordini (cliente_id, data_ordine) VALUES (:cliente_id, :data_ordine)";
                $stmt = $conn->prepare($sqlInsert);
                $stmt->bindParam(':cliente_id', $cliente_id);
                $stmt->bindParam(':data_ordine', $data_ordine);
                $stmt->execute();

                echo "<script type='text/javascript'>alert('Nuovo ordine aggiunto con successo!');</script>";
                echo "<script>window.location.href = 'ordini.php';</script>";

        } else if (!empty($_POST['id_elimina'])) {
            $id_elimina = $_POST['id_elimina'];

            $sqlDelete = "DELETE FROM ordini WHERE id = :id";
            $stmt = $conn->prepare($sqlDelete);
            $stmt->bindParam(':id', $id_elimina);
            $stmt->execute();

            echo "<script type='text/javascript'>alert('Ordine eliminato con successo!');</script>";
            echo "<script>window.location.href = 'ordini.php';</script>";
        } else if (!empty($_POST['id_modifica'])) {
            $id_modifica = $_POST['id_modifica'];
            $updateFields = array();

            if (!empty($_POST['cliente_id_modificato'])) {
                $updateFields[] = "cliente_id = :cliente_id";
            }
            if (!empty($_POST['data_ordine_modificato'])) {
                $updateFields[] = "data_ordine = :data_ordine";
            }

            if (!empty($updateFields)) {
                $sqlUpdate = "UPDATE ordini SET " . implode(", ", $updateFields) . " WHERE id = :id";
                $stmt = $conn->prepare($sqlUpdate);
                foreach ($updateFields as $field) {
                    $fieldName = substr($field, 0, strpos($field, ' '));
                    $stmt->bindParam(':' . $fieldName, $_POST[$fieldName . '_modificato']);
                }
                $stmt->bindParam(':id', $id_modifica);
                $stmt->execute();

                    echo "<script type='text/javascript'>alert('Ordine modificato con successo!');</script>";
                    echo "<script>window.location.href = 'ordini.php';</script>";
                } else {
                    echo "<p>Nessun campo da modificare è stato compilato.</p>";
                }
            }
            else {
                echo '<script type="text/javascript">
                window.onload = function () { alert("Tutti i campi sono obbligatori!"); } 
                </script>'; 
            }
        }

    $sqlClienti = "SELECT * FROM clienti";
    $statementClienti = $conn->query($sqlClienti);
    $clienti = $statementClienti->fetchAll(PDO::FETCH_ASSOC);

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

    echo "<div>
            <h1>Modifica Ordine</h1>
            <form method='POST'>
                <label for='id_modifica'>Seleziona ID dell'ordine da modificare:</label>
                <select name='id_modifica' id='id_modifica'>";
    $sqlSelect = "SELECT id, data_ordine FROM ordini";
    $stmt = $conn->query($sqlSelect);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['data_ordine'] . "</option>";
    }
    echo "</select><br><br>";

    echo "<label for='cliente_id_modificato'>Nuovo Cliente:</label>
            <select name='cliente_id_modificato'>";
            foreach ($clienti as $cliente) {
                echo "<option value='{$cliente['id']}'>{$cliente['nome']} {$cliente['cognome']}</option>";
            }
echo "</select><br><br>

            <label for='data_ordine_modificato'>Nuova Data Ordine:</label>
            <input type='date' name='data_ordine_modificato'><br><br>

            <button type='submit'>Modifica Ordine</button>
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
