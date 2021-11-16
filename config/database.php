<?php

// config/database.php 

/* Questo è il file core del programma e della connessione. Qui viene costruito l'oggetto PDO ed inizializzato.
Postilla su PDO: Perchè lo abbiamo scelto?  Per l'SQL Injiection.
Infatti abbiamo voluto servirci dei prepare statements di PDO per far sì che prima che la query venga eseguita,
si possano scambiare (con bindparam ndr.) i segnaposto (:segnaposto) con i parametri corretti ($variabile..spesso $id, $pid, $bid ecc..)
Inoltre, PDO è più performante secondo noi per la gestione degli errori.
Usando il costrutto Try Catch è possibile servirsi dell'oggetto PDO per recuperare l'errore (PDOException), assegnarlo a una variabile ($e)
e stamparlo a video nel qual caso la query non andasse a buon fine ($e->getMessage()) */


// Inizializzo un vettore per la configurazione
$config = [
    'db_engine' => 'mysql',
    'db_host' => 'localhost',
    'db_name' => 'my_webcreativedesign',
    'db_user' => 'webcreativedesign',
    'db_password' => '',
];


// Creo una stringa con i parametri del vettore
$db_config = $config['db_engine'] . ":host=" . $config['db_host'] . ";dbname=" . $config['db_name'];


// La stringa viene data in pasto al costrutto try che crea l'oggetto PDO, definisco la codifica, attivo l'ERR MODE di PDO per la generazione di errori, attivo i prepare statements
try {
    $pdo = new PDO($db_config, $config['db_user'], $config['db_password'], [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);   // Questo garantisce maggiore sicurezza, la query e i dati effettivi vengono inviati separatamente, il che aumenta la sicurezza. Sostanzialmente quando i parametri vengono passati alla query, i tentativi di SQL Injiection in essi vengono bloccati.
} catch (PDOException $e) {
    exit("Impossibile connettersi al database: " . $e->getMessage());
}

// Catch mi stampa a video l'errore di connessione ed esce dallo script (exit)
