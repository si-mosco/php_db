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

    echo "<h1>Benvenuto negli Ordini " . $_SESSION["UTENTE"]. "</h1>";

    echo "<footer>
        <button onclick='redirectToPage(\"aggiungi_ordine.php\")'>Gestisci ordini</button>
        <button onclick='redirectToPage(\"protetta.php\")'>Visualizza i Clienti</button>
        <button onclick='redirectToPage(\"oggetti.php\")'>Visualizza negli Oggetti</button>
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

        // Query che stampa tutti gli ordini o ordina per ID
        $sql = 'SELECT o.id, CONCAT(c.nome, " ", c.cognome) AS cliente, o.data_ordine FROM ordini o LEFT JOIN clienti c ON o.cliente_id = c.id';

        // Applica i filtri se sono stati impostati
        $whereClause = '';
        $parameters = [];
        if (!empty($_POST['cliente'])) {
            $whereClause .= 'AND CONCAT(c.nome, " ", c.cognome) LIKE ? ';
            $parameters[] = '%' . $_POST['cliente'] . '%';
        }
        if (!empty($_POST['data'])) {
            $whereClause .= 'AND DATE(o.data_ordine) = ? ';
            $parameters[] = $_POST['data'];
        }

        // Costruisci la query finale
        if (!empty($whereClause)) {
            $sql .= ' WHERE ' . ltrim($whereClause, 'AND ');
        }

        // Verifica se l'utente ha selezionato un filtro per l'ordinamento
        if (isset($_POST['ordine'])) {
            $ordinamento = $_POST['ordine'];

            if ($ordinamento === 'Id: Crescente') {
                $sql .= ' ORDER BY o.id ASC';
            } elseif ($ordinamento === 'Id: Decrescente') {
                $sql .= ' ORDER BY o.id DESC';
            }
        }

        $statement = $conn->prepare($sql);
        $statement->execute($parameters);

        if ($statement->rowCount() > 0) {
            // Tabella HTML
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Data</th>
                    </tr>";

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'] ?? '-';
                $cliente = $row['cliente'] ?? '-';
                $data_ordine = $row['data_ordine'] ?? '-';

                echo "<tr>
                        <td>{$id}</td>
                        <td>{$cliente}</td>
                        <td>{$data_ordine}</td>
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

                    <label for='cliente'>Nome Cliente:</label>
                    <input type='text' name='cliente'><br><br>

                    <label for='data'>Data:</label>
                    <input type='date' name='data'><br><br>

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
