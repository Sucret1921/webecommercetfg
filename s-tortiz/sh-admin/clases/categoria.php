<?php
// Clase para gestión de categorías
class Categoria {
    private $con;
    public function __construct($con) {
        $this->con = $con;
    }
    public function getAll() {
        $sql = "SELECT * FROM categoria ORDER BY nombre ASC";
        return $this->con->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get($id) {
        $sql = "SELECT * FROM categoria WHERE id=?";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function add($nombre, $descripcion, $icono, $color) {
        $sql = "INSERT INTO categoria (nombre, descripcion, icono, color) VALUES (?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $icono, $color]);
    }
    public function update($id, $nombre, $descripcion, $icono, $color) {
        $sql = "UPDATE categoria SET nombre=?, descripcion=?, icono=?, color=? WHERE id=?";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $icono, $color, $id]);
    }
    public function delete($id) {
        $sql = "DELETE FROM categoria WHERE id=?";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([$id]);
    }
}
