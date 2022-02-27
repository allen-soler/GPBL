<?php 
    $host = 'hdb.myd.infomaniak.com';
    $db = 'GPBL_JorgeAllen';
    $user = 'hdb_temp_3';
    $pass = 'Gtmkih4Oxzac';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        $pdo = new PDO($dsn,$user,$pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        throw new PDOException($e->getMessage());
    }
    require_once 'crud.php';
    $crud = new crud($pdo);