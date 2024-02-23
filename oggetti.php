<?php
session_start();
require "libreria.php"; // per funzioni che verranno eseguite dal server e che possono servire 
require "credenziali.php"; //per tenere le credenziali di connessione al database

if (isset($_SESSION["UTENTE"])) {
    echo "<html>
        <head>
        <link rel='stylesheet' href='mystyle.css'>
        </head>
        <body>";

    echo "<h1>Benvenuto negli Oggetti " . $_SESSION["UTENTE"]. "</h1>";

    echo "<footer>
        <button onclick='redirectToPage(\"aggiungi_oggetto.php\")'>Gestisci oggetti</button>
        <button onclick='redirectToPage(\"protetta.php\")'>Visualizza i Clienti</button>
        <button onclick='redirectToPage(\"ordini.php\")'>Visualizza negli Ordini</button>
        <button onclick='redirectToPage(\"luoghi.php\")'>Visualizza i Luoghi</button>
        <button onclick='redirectToPage(\"oggetti_ordini.php\")'>Visualizza gli Oggetti nei Ordini</button>
      </footer><br>";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Controlla se la pagina è stata ricaricata
        if (isset($_POST['ricaricaPagina'])) {
            // Imposta la variabile di sessione
            $_SESSION['ricaricaPagina'] = true;
            // Ricarica la pagina
            echo "<script>window.location.reload();</script>";
        }

        // Query che stampa tutti gli oggetti o ordina per ID
        $sql = 'SELECT * FROM oggetti';

        // Verifica se ci sono filtri applicati
        $params = [];
        if (isset($_POST['nome']) && $_POST['nome'] != '') {
            $sql .= ' WHERE nome LIKE ?';
            $params[] = '%' . $_POST['nome'] . '%';
        }

        if (isset($_POST['costo']) && $_POST['costo'] != '' && isset($_POST['operatore'])) {
            $operatore = ($_POST['operatore'] == 'maggiore') ? '>' : '<';
            $sql .= (isset($_POST['nome']) && $_POST['nome'] != '') ? ' AND costo ' . $operatore . ' ?' : ' WHERE costo ' . $operatore . ' ?';
            $params[] = $_POST['costo'];
        }

        if (isset($_POST['ordine'])) {
            $ordinamento = $_POST['ordine'];
            if ($ordinamento === 'Id: Crescente') {
                $sql .= ' ORDER BY id ASC';
            } elseif ($ordinamento === 'Id: Decrescente') {
                $sql .= ' ORDER BY id DESC';
            }
        }

        // Prepara la query con i parametri
        $statement = $conn->prepare($sql);
        $statement->execute($params);

        if ($statement->rowCount() > 0) {
            // Tabella HTML
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Costo</th>
                    </tr>";

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'] ?? '-';
                $nome = $row['nome'] ?? '-';
                $costo = $row['costo'] ?? '-';

                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$nome}</td>
                        <td>{$costo}</td>
                    </tr> ";
            }

            echo "</table>";

            // Form per i filtri
            echo "<form method='POST'>
                    <label for='ordine'>Ordine:</label>
                    <select name='ordine'>
                        <option>Id: Crescente</option>
                        <option>Id: Decrescente</option>
                    </select><br><br>

                    <label for='nome'>Nome dell'oggetto:</label>
                    <input type='text' name='nome'><br><br>

                    <label for='operatore'>Operatore:</label>
                    <select name='operatore'>
                        <option value='maggiore'>Maggiore di</option>
                        <option value='minore'>Minore di</option>
                    </select><br><br>

                    <label for='costo'>Costo:</label>
                    <input type='number' name='costo'><br><br>

                    <button type='submit'>Filtra</button>
                </form>";

        } else {
            // Nessun risultato trovato
            echo "Nessun risultato trovato";
        }

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    } finally {
        // Chiudi la connessione in ogni caso
        $conn = null;
    }

    echo "</body>
        </html>";
} else {
    echo "Accesso non consentito";
}
?>

<script>
    function redirectToPage(page) {
        window.location.href = page;
    }
</script>
