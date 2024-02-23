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

    echo "<h1>Benvenuto negli Oggetti di ogni Ordine " . $_SESSION["UTENTE"]. "</h1>";

    echo "<footer>
        <button onclick='redirectToPage(\"aggiungi_ordineoggetto.php\")'>Gestisci oggetti negli ordini</button>
        <button onclick='redirectToPage(\"protetta.php\")'>Visualizza i Clienti</button>
        <button onclick='redirectToPage(\"oggetti.php\")'>Visualizza negli Oggetti</button>
        <button onclick='redirectToPage(\"luoghi.php\")'>Visualizza i Luoghi</button>
        <button onclick='redirectToPage(\"ordini.php\")'>Visualizza gli Ordini</button>
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

        // Query che stampa tutti gli oggetti_ordini o ordina per ID
        $sql = 'SELECT oo.id, oo.id_ordini, og.nome AS nome_oggetto FROM ordini_oggetti oo LEFT JOIN oggetti og ON oo.id_oggetti = og.id';

        // Verifica se sono stati applicati filtri per nome dell'oggetto e ID dell'ordine
        $params = [];
        $whereClause = '';
        if (!empty($_POST['nome_oggetto'])) {
            $whereClause .= ' AND og.nome LIKE ?';
            $params[] = '%' . $_POST['nome_oggetto'] . '%';
        }
        if (!empty($_POST['id_ordine'])) {
            $whereClause .= ' AND oo.id_ordini = ?';
            $params[] = $_POST['id_ordine'];
        }

        // Aggiungi la clausola WHERE alla query se sono stati specificati filtri per nome dell'oggetto e ID dell'ordine
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
                        <th>Ordine</th>
                        <th>Oggetto</th>
                    </tr>";

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'] ?? '-';
                $id_ordini = $row['id_ordini'] ?? '-';
                $nome_oggetto = $row['nome_oggetto'] ?? '-';


                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$id_ordini}</td>
                        <td>{$nome_oggetto}</td>
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

                    <label for='nome_oggetto'>Nome Oggetto:</label>
                    <input type='text' name='nome_oggetto'><br><br>

                    <label for='id_ordine'>ID Ordine:</label>
                    <input type='text' name='id_ordine'><br><br>

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
