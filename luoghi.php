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

    echo "<h1>Benvenuto nei Luoghi " . $_SESSION["UTENTE"]. "</h1>";

    echo "<footer>
        <button onclick='redirectToPage(\"aggiungi_luogo.php\")'>Gestisci luoghi</button>
        <button onclick='redirectToPage(\"protetta.php\")'>Visualizza i Clienti</button>
        <button onclick='redirectToPage(\"oggetti.php\")'>Visualizza negli Oggetti</button>
        <button onclick='redirectToPage(\"ordini.php\")'>Visualizza gli Ordini</button>
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

        // Query che stampa tutti i luoghi o ordina per ID
        $sql = 'SELECT * FROM luoghi_consegna';

        // Verifica se sono stati applicati filtri per città e nazione
        $params = [];
        $whereClause = '';
        if (!empty($_POST['citta'])) {
            $whereClause .= ' AND citta LIKE ?';
            $params[] = '%' . $_POST['citta'] . '%';
        }
        if (!empty($_POST['nazione'])) {
            $whereClause .= ' AND nazione LIKE ?';
            $params[] = '%' . $_POST['nazione'] . '%';
        }

        // Aggiungi la clausola WHERE alla query se sono stati specificati filtri per città e nazione
        if (!empty($whereClause)) {
            $sql .= ' WHERE ' . ltrim($whereClause, ' AND ');
        }

        // Verifica se l'utente ha selezionato un filtro
        if (isset($_POST['my_html_select_box'])) {
            $filtro = $_POST['my_html_select_box'];

            // Modifica la query in base alla selezione dell'utente
            if ($filtro === 'Id: Crescente') {
                $sql .= ' ORDER BY id ASC';
            } elseif ($filtro === 'Id: Decrescente') {
                $sql .= ' ORDER BY id DESC';
            }
        }

        $statement = $conn->prepare($sql);
        $statement->execute($params);

        if ($statement->rowCount() > 0) {
            // Tabella HTML
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nazione</th>
                        <th>Città</th>
                        <th>CAP</th>
                        <th>Via</th>
                        <th>N° Civico</th>
                    </tr>";

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'] ?? '-';
                $nazione = $row['nazione'] ?? '-';
                $citta = $row['citta'] ?? '-';
                $cap = $row['cap'] ?? '-';
                $via = $row['via'] ?? '-';
                $num_civico = $row['num_civico'] ?? '-';             
                
                echo "<tr>
                        <td>{$id}</td>
                        <td>{$nazione}</td>
                        <td>{$citta}</td>
                        <td>{$cap}</td>
                        <td>{$via}</td>
                        <td>{$num_civico}</td>
                    </tr> ";
            }

            echo "</table>";

            // Form per il filtro
            echo "<form method='POST'>
                    <label for='my_html_select_box'>FILTRA PER:</label>    
                    <select name='my_html_select_box'>
                        <option>Id: Crescente</option>
                        <option>Id: Decrescente</option>
                    </select><br><br>

                    <label for='citta'>Città:</label>
                    <input type='text' name='citta'><br><br>

                    <label for='nazione'>Nazione:</label>
                    <input type='text' name='nazione'><br><br>

                    <button type='submit'>Filtra</button>
                </form>";

        } else {
            // Messaggio se la query non ha prodotto risultati
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
