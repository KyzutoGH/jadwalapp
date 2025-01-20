<?php
class JadwalController {
    private $db;
    private $model;

    public function __construct($database) {
        $this->db = $database;
        $this->model = new JadwalModel($database);
    }

    public function create($data) {
        return $this->model->create($data);
    }

    public function read($id = null) {
        return $this->model->read($id);
    }

    public function update($id, $data) {
        return $this->model->update($id, $data);
    }

    public function delete($id) {
        return $this->model->delete($id);
    }
}
?>