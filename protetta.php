<?php
session_start();
require "libreria.php"; // per funzioni che verranno eseguite dal server e che possono servire 
require "credenziali.php"; //per tenere le credenziali di connessione al database

if (isset($_SESSION["UTENTE"])) {

    // css per presentazione più belina
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

    echo "<h1>Benvenuto nei Clienti " . $_SESSION["UTENTE"]. "</h1>";

    echo "<footer>
        <button onclick='redirectToPage(\"aggiungi_utente.php\")'>Gestisci utenti</button>
        <button onclick='redirectToPage(\"ordini.php\")'>Visualizza gli Ordini</button>
        <button onclick='redirectToPage(\"oggetti.php\")'>Visualizza gli Oggetti</button>
        <button onclick='redirectToPage(\"luoghi.php\")'>Visualizza i Luoghi</button>
        <button onclick='redirectToPage(\"oggetti_ordini.php\")'>Visualizza gli Oggetti nei Ordini</button>
      </footer><br>";

    //connessione per la stampa della tabella principale se la pagina non è ricaricata
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Controlla se la pagina è stata ricaricata
        if (isset($_POST['ricaricaPagina'])) {
            
            $_SESSION['ricaricaPagina'] = true;
            // Ricarica la pagina
            echo "<script>window.location.reload();</script>";
        }

        // Query principale per i clienti
        $sql = 'SELECT c.id, c.nome, c.cognome, c.email, l.citta, l.via, l.num_civico FROM clienti c LEFT JOIN luoghi_consegna l ON c.id_luogo = l.id';

        // Condizioni iniziali per i filtri
        $filtroCognome = isset($_POST['cognome_select_box']) ? $_POST['cognome_select_box'] : '';
        $filtroNome = isset($_POST['nome_select_box']) ? $_POST['nome_select_box'] : '';
        $filtroId = isset($_POST['id_select_box']) ? $_POST['id_select_box'] : '';

        // Applica i filtri solo se sono stati impostati
        $whereClause = "";
        if (!empty($filtroCognome)) {
            $whereClause .= "c.cognome LIKE '%" . $filtroCognome . "%' AND ";
        }
        if (!empty($filtroNome)) {
            $whereClause .= "c.nome LIKE '%" . $filtroNome . "%' AND ";
        }

        // Rimuove l'eventuale "AND" finale dalla condizione WHERE
        if (!empty($whereClause)) {
            $whereClause = rtrim($whereClause, " AND ");
            $sql .= " WHERE " . $whereClause;
        }

        // Modifica la query in base alla selezione dell'utente per l'ordinamento
        if ($filtroId === 'ID: Crescente') {
            $sql .= ' ORDER BY c.id ASC';
        } elseif ($filtroId === 'ID: Decrescente') {
            $sql .= ' ORDER BY c.id DESC';
        }

        // Esegui la query
        $statement = $conn->query($sql);

        if ($statement->rowCount() > 0) {
            // Tabella HTML
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                        <th>Luogo</th>
                    </tr>";

            // Itera sui risultati e stampa le righe della tabella
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                // Sostituisce i valori NULL con '-'
                $id = $row['id'] ?? '-';
                $nome = $row['nome'] ?? '-';
                $cognome = $row['cognome'] ?? '-';
                $email = $row['email'] ?? '-';
                $citta = $row['citta'] ?? '-';
                $via = $row['via'] ?? '-';
                $num_civico = $row['num_civico'] ?? '-';
            
                echo "<tr>
                        <td>{$id}</td>
                        <td>{$nome}</td>
                        <td>{$cognome}</td>
                        <td>{$email}</td>";
                        if ($citta != '-'){
                            echo "<td>{$citta} - {$via} n° {$num_civico}</td>";
                        }
                        else{
                            echo "<td>{$citta}</td>";
                        }
                        echo "</tr> ";
            }
            

            echo "</table>";

            // Form per i filtri
            echo "<form method='POST'>
                    <label for='id_select_box'>FILTRA PER ID:</label>    
                    <select name='id_select_box'>
                        <option>ID: Crescente</option>
                        <option>ID: Decrescente</option>
                    </select><br><br>

                    <label for='cognome_select_box'>FILTRA PER COGNOME:</label>
                    <input type='text' name='cognome_select_box'><br><br>

                    <label for='nome_select_box'>FILTRA PER NOME:</label>
                    <input type='text' name='nome_select_box'><br><br>

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
