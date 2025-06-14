
<?php
class ProductIterator implements Iterator {
    private string $table;
    private array $criteria;
    private PDOStatement $stmt;
    private $current;
    private int $position = 0;
    private bool $valid = false;

    public function __construct(string $table, array $criteria = []) {
        $this->table = $table;
        $this->criteria = $criteria;
        $this->prepareStatement();
    }

    private function getConnection(): PDO {
        return Database::getInstance()->getConnection();
    }

    private function prepareStatement(): void {
        $where = '';
        $params = [];
        
        if (!empty($this->criteria)) {
            $conditions = [];
            foreach ($this->criteria as $field => $value) {
                $conditions[] = "$field = ?";
                $params[] = $value;
            }
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }
        
        $query = "SELECT * FROM {$this->table} $where";
        $this->stmt = $this->getConnection()->prepare($query);
        $this->stmt->execute($params);
    }

    public function rewind(): void {
        $this->position = 0;
        $this->current = $this->stmt->fetch(PDO::FETCH_ASSOC);
        $this->valid = $this->current !== false;
    }

    public function current(): mixed {
        return $this->current;
    }

    public function key(): int {
        return $this->position;
    }

    public function next(): void {
        $this->position++;
        $this->current = $this->stmt->fetch(PDO::FETCH_ASSOC);
        $this->valid = $this->current !== false;
    }

    public function valid(): bool {
        return $this->valid;
    }

    public function getRelatedData(int $productId, string $relation): array {
        $stmt = $this->getConnection()->prepare("SELECT * FROM {$relation} WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>