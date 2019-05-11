<?php
/**
 * Created by PhpStorm.
 * User: xradi
 * Date: 11/05/2019
 * Time: 15:16
 */

class DataTables
{
    public function getUsersData()
    {
// DB table to use
        $table = 'usuarios';

// Table's primary key
        $primaryKey = 'id_usuario';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
        $columns = array(
            array('db' => 'nombre', 'dt' => 0),
            array('db' => 'nif', 'dt' => 1),
            array('db' => 'email', 'dt' => 2),
            array('db' => 'id_usuario', 'dt' => 3)
        );

// SQL server connection information
        $sql_details = array(
            'user' => 'empo',
            'pass' => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'db' => 'empo_empo',
            'host' => 'mysql-empo.alwaysdata.net'
        );


        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */

        require('ssp.class.php');

        return json_encode(
            SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
        );
    }
}