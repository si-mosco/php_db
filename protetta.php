<?php
session_start();
require "libreria.php"; // per funzioni che verranno eseguite dal server e che possono servire 
require "credenziali.php"; //per tenere le credenziali di connessione al database

if (isset($_SESSION["UTENTE"])) {

    // css per presentazione più belina
    echo "<html>
        <head>
        </head>
        <body>";

    echo "Benvenuto nei Clienti " . $_SESSION["UTENTE"];

    echo "<footer>
        <button onclick='redirectToPage(\"ordini.php\")'>Visualizza gli Ordini</button>
        <button onclick='redirectToPage(\"oggetti.php\")'>Visualizza gli Oggetti</button>
      </footer><br>";

    //connessione per la stampa della tabella principale se la pagina non è ricaricata
    try {
        //$user = "nicola";
        //$passwd = "1234";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Controlla se la pagina è stata ricaricata
        if (isset($_POST['ricaricaPagina'])) {
            
            $_SESSION['ricaricaPagina'] = true;
            // Ricarica la pagina
            echo "<script>window.location.reload();</script>";
        }

        // Query che stampa tutti i clienti (query principale)
        $sql = 'SELECT * FROM clienti';

        // Verifica se l'utente ha selezionato un filtro per il prezzo
        if (isset($_POST['my_html_select_box']) && isset($_POST['select_nomi'])) {
            $filtroId = $_POST['my_html_select_box'];
            $filtroNomi = $_POST['select_nomi'];
        
            // Aggiungi la condizione WHERE per il filtro dei nomi
            $sql .= " WHERE nome LIKE '%" . $filtroNomi . "%'";
        
            // Modifica la query in base alla selezione dell'utente
            if ($filtroId === 'ID: Crescente') {
                $sql .= ' ORDER BY id ASC';
            } elseif ($filtroId === 'ID: Decrescente') {
                $sql .= ' ORDER BY id DESC';
            }
        }

        $statement = $conn->query($sql);

        if ($statement->rowCount() > 0) {
            // Tabella HTML
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                    </tr>";

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nome']}</td>
                        <td>{$row['cognome']}</td>
                        <td>{$row['email']}</td>
                    </tr> ";
            }

            echo "</table>";

            // Form per il filtro
            
            echo"<form method='POST'>";
            //echo "<form method='POST'>
             echo"<label for='my_html_select_box'>FILTRA PER ID: </label>    
                    <select name='my_html_select_box'>
                        <option>ID: Crescente</option>
                        <option>ID: Decrescente</option>
                    </select>
                    <button type='submit'>Filtra</button><br><br>";


            try{
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                


                $queryvenditori = 'SELECT DISTINCT nome FROM clienti';
                $statementforn = $conn->prepare($queryvenditori);
                $statementforn->execute();
                $nomi = $statementforn->fetchAll();

                echo'<label>FILTRA PER nome: </label>';
                echo '<select name="select_nomi">';
                echo' <option> </option>';
                foreach ($nomi as $nome) {
                    echo'  <option>' . $nome['nome'] . '</option>';
                }
                echo '</select>';
                
                echo"</form>"; //fine form filtri nomi
                
            } catch (PDOException $e) {echo "Connection failed: " . $e->getMessage();}


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