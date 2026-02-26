<?php
class Database
{
    private $conn;

    public function __construct()
    {
        $this->connect();
    }

    public function getInsertId() {
        return $this->conn->insert_id;
    }

    private function connect()
    {
        $this->conn = new mysqli("localhost", "root", "", "evde_bakim");

        if ($this->conn->connect_error) {
            die("Database connection failed");
        }

        $this->conn->set_charset("utf8mb4");
    }

    /* Core query */
    private function run(string $sql, ?string $types = null, ?array $params = null)
    {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Query prepare error: " . $this->conn->error);
        }

        // Sadece parametre varsa bind et
        if (is_array($params) && count($params) > 0 && !empty($types)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt;
    }



    /* SELECT single */
    public function fetch(string $sql, ?string $types = null, ?array $params = null): ?array
    {
        $stmt = $this->run($sql, $types, $params);
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    /* SELECT multiple */
    public function fetchAll(string $sql, ?string $types = null, ?array $params = null): array
    {
        $stmt = $this->run($sql, $types, $params);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* INSERT / UPDATE / DELETE */
    public function execute(string $sql, ?string $types = null, ?array $params = null): bool
    {
        $stmt = $this->run($sql, $types, $params);
        return $stmt->affected_rows > 0;
    }

    public function lastInsertId(): int
    {
        return $this->conn->insert_id;
    }
}
