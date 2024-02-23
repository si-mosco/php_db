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
        if (!empty($_POST['citta']) && !empty($_POST['nazione']) && !empty($_POST['cap']) && !empty($_POST['via']) && !empty($_POST['num_civico'])) {
            $citta = $_POST['citta'];
            $nazione = $_POST['nazione'];
            $cap = $_POST['cap'];
            $via = $_POST['via'];
            $num_civico = $_POST['num_civico'];

            $sqlInsert = "INSERT INTO luoghi_consegna (citta, nazione, cap, via, num_civico) VALUES (:citta, :nazione, :cap, :via, :num_civico)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bindParam(':citta', $citta);
            $stmt->bindParam(':nazione', $nazione);
            $stmt->bindParam(':cap', $cap);
            $stmt->bindParam(':via', $via);
            $stmt->bindParam(':num_civico', $num_civico);
            $stmt->execute();

            echo "<script type='text/javascript'>alert('Nuovo luogo di consegna aggiunto con successo!');</script>";
                echo "<script>window.location.href = 'luoghi.php';</script>";
        } else if (!empty($_POST['id_elimina'])) {
            $id_elimina = $_POST['id_elimina'];

            $sqlDelete = "DELETE FROM luoghi_consegna WHERE id = :id";
            $stmt = $conn->prepare($sqlDelete);
            $stmt->bindParam(':id', $id_elimina);
            $stmt->execute();

            echo "<script type='text/javascript'>alert('Luogo di consegna eliminato con successo!');</script>";
                echo "<script>window.location.href = 'luoghi.php';</script>";
        } else if (!empty($_POST['id_modifica'])) {
            $id_modifica = $_POST['id_modifica'];
            $updateFields = array();

            if (!empty($_POST['citta_modificato'])) {
                $updateFields[] = "citta = :citta";
            }
            if (!empty($_POST['nazione_modificato'])) {
                $updateFields[] = "nazione = :nazione";
            }
            if (!empty($_POST['cap_modificato'])) {
                $updateFields[] = "cap = :cap";
            }
            if (!empty($_POST['via_modificato'])) {
                $updateFields[] = "via = :via";
            }
            if (!empty($_POST['num_civico_modificato'])) {
                $updateFields[] = "num_civico = :num_civico";
            }

            if (!empty($updateFields)) {
                $sqlUpdate = "UPDATE luoghi_consegna SET " . implode(", ", $updateFields) . " WHERE id = :id";
                $stmt = $conn->prepare($sqlUpdate);
                foreach ($updateFields as $field) {
                    $fieldName = substr($field, 0, strpos($field, ' '));
                    $stmt->bindParam(':' . $fieldName, $_POST[$fieldName . '_modificato']);
                }
                $stmt->bindParam(':id', $id_modifica);
                $stmt->execute();

                echo "<script type='text/javascript'>alert('Luogo modificato con successo!');</script>";
                echo "<script>window.location.href = 'luoghi.php';</script>";
            } else {
                echo "<p>Nessun campo da modificare è stato compilato.</p>";
            } 
        } else {
            echo '<script type="text/javascript">
            window.onload = function () { alert("Tutti i campi sono obbligatori!"); } 
            </script>'; 
        }
    }

    echo "<div>
            <h1>Aggiungi Luogo di Consegna</h1>
            <form method='POST'>
                <label for='citta'>Città:</label>
                <input type='text' name='citta'><br><br>

                <label for='nazione'>Nazione:</label>
                <input type='text' name='nazione'><br><br>

                <label for='cap'>CAP:</label>
                <input type='text' name='cap'><br><br>

                <label for='via'>Via:</label>
                <input type='text' name='via'><br><br>

                <label for='num_civico'>Numero Civico:</label>
                <input type='text' name='num_civico'><br><br>

                <button type='submit'>Aggiungi Luogo di Consegna</button>
            </form>
        </div>";

    echo "<div>
            <h1>Elimina Luogo di Consegna</h1>
            <form method='POST'>
                <label for='id_elimina'>Seleziona ID del luogo di consegna da eliminare:</label>
                <select name='id_elimina' id='id_elimina'>";
    $sqlSelect = "SELECT id, citta, nazione FROM luoghi_consegna";
    $stmt = $conn->query($sqlSelect);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['citta'] . ", " . $row['nazione'] . "</option>";
    }
    echo "</select><br><br>
                <button type='submit'>Elimina Luogo di Consegna</button>
            </form>
        </div>";

        echo "<div>
        <h1>Modifica Luogo di Consegna</h1>
        <form method='POST'>
                <label for='id_modifica'>Seleziona ID del luogo di consegna da eliminare:</label>
                <select name='id_modifica' id='id_modifica'>";
    $sqlSelect = "SELECT id, citta, nazione FROM luoghi_consegna";
    $stmt = $conn->query($sqlSelect);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['citta'] . ", " . $row['nazione'] . "</option>";
    }
    echo "</select><br><br>";

echo "<label for='citta_modificato'>Nuova Città:</label>
<input type='text' name='citta_modificato'><br><br>

<label for='nazione_modificato'>Nuova Nazione:</label>
<input type='text' name='nazione_modificato'><br><br>

<label for='cap_modificato'>Nuovo CAP:</label>
<input type='text' name='cap_modificato'><br><br>

<label for='via_modificato'>Nuova Via:</label>
<input type='text' name='via_modificato'><br><br>

<label for='num_civico_modificato'>Nuovo Numero Civico:</label>
<input type='text' name='num_civico_modificato'><br><br>";

echo "<button type='submit'>Modifica Luogo di Consegna</button>
            </form>
        </div>";

    // JavaScript per reindirizzare alla pagina principale
    echo "<script>
            function goToHomePage() {
                window.location.href = 'luoghi.php';
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