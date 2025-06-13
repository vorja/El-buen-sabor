<?php
// models/MySQL.php

//Clase para gestionar la conexion a la base de datos
class MySQL {

    //Datos de conexion
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "elbuensabor";
    private $conn;

    public function __construct() {
        $this->connect();
    }

    public function connect() {
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }

            $this->conn->set_charset("utf8");
        } catch (Exception $e) {
            throw new Exception("Error al conectar con la base de datos: " . $e->getMessage());
        }
    }

    //Metodo para desconectar la base de datos
    public function desconectar(){
        if($this->conn){
            $this->conn->close(); // Usar el método close() del objeto
        }
    }

    // Método para obtener el objeto de conexión mysqli
    // ESTE ES EL MÉTODO CRUCIAL QUE FALTABA
    public function getConexion() {
        return $this->conn;
    }

    // Metodo para preparar una consulta (usando el objeto mysqli)
    public function prepare($query) {
        if (!$this->conn) {
            $this->connect();
        }
        return $this->conn->prepare($query);
    }

    // Metodo para ejecutar una consulta y devolver su resultaddo
    // Nota: Es mejor usar prepared statements para todas las consultas con datos de usuario
    // pero si mantienes esta, asegúrate de que no haya inyección SQL.
    public function efectuarConsulta($consulta){
        // Ya se estableció utf8 en conectar, pero si es necesario aquí:
         $this->conn->query("SET NAMES 'utf8'");
         $this->conn->query("SET CHARACTER SET 'utf8'");

        $resultado = $this->conn->query($consulta); // Usar el método query() del objeto

        if(!$resultado){
            echo "Error en la consulta : " .$this->conn->error; // Acceder a error del objeto
        }

        return $resultado;
    }

     public function escape_string($string) {
        if ($this->conn) {
            return mysqli_real_escape_string($this->conn, $string);
        }
        return $string; // Retorna sin escapar si no hay conexión, aunque esto no debería ocurrir
    }
    
    // Puedes añadir estos métodos para encapsular las transacciones
    // Si los añades, podrías llamar a $mysql->beginTransaction() en lugar de $mysql->getConexion()->beginTransaction()
    /*
    public function beginTransaction() {
        $this->conn->begin_transaction();
    }

    public function commit() {
        $this->conn->commit();
    }

    public function rollback() {
        $this->conn->rollback();
    }

    public function insertId() {
        return $this->conn->insert_id;
    }
    */

    public function insert($query, $params = []) {
        if (!$this->conn) {
            $this->connect();
        }
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>