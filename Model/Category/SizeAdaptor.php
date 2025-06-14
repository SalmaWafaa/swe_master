<?php

class SizeAdapter {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function convert(string $size, string $fromSystem, string $toSystem): string {
        if ($fromSystem === $toSystem) {
            return $size;
        }

        $query = "SELECT sc.to_size 
                 FROM size_conversions sc
                 JOIN size_systems ss_from ON sc.from_system_id = ss_from.id
                 JOIN size_systems ss_to ON sc.to_system_id = ss_to.id
                 WHERE ss_from.name = :fromSystem 
                 AND ss_to.name = :toSystem
                 AND sc.from_size = :size";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':fromSystem' => $fromSystem,
            ':toSystem' => $toSystem,
            ':size' => $size
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new InvalidArgumentException(
                "No conversion found for size $size from $fromSystem to $toSystem"
            );
        }

        return $result['to_size'];
    }
}
?>
