<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    function conecta() {
        //$con = new mysqli('localhost', getenv("usuariodb"), getenv("passworddb"), getenv("nombredb"));
        //$con ->set_charset("utf8");
        $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $con = new PDO('mysql:host=localhost;dbname='.getenv("nombredb"), getenv("usuariodb"), getenv("passworddb"), $opciones);
        $con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }
?>