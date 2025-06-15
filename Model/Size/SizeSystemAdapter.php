<?php
require_once __DIR__ . '/../../config/dbConnectionSingelton.php';
require_once 'SizeSystemInterface.php';

class SizeSystemAdapter implements SizeSystemInterface {
    private $db;
    private $systemId;
    private $systemName;

    public function __construct(int $systemId, string $systemName) {
        $this->db = Database::getInstance()->getConnection();
        $this->systemId = $systemId;
        $this->systemName = $systemName;
    }

    public function convertSize(string $size, string $targetSystem): string {
        try {
            // Get target system ID
            $stmt = $this->db->prepare("SELECT id FROM size_systems WHERE name = ?");
            $stmt->execute([$targetSystem]);
            $targetSystemId = $stmt->fetchColumn();

            if (!$targetSystemId) {
                throw new Exception("Target size system not found");
            }

            // Get conversion
            $stmt = $this->db->prepare("
                SELECT to_size 
                FROM size_conversions 
                WHERE from_system_id = ? 
                AND to_system_id = ? 
                AND from_size = ?
            ");
            $stmt->execute([$this->systemId, $targetSystemId, $size]);
            $convertedSize = $stmt->fetchColumn();

            if (!$convertedSize) {
                throw new Exception("No conversion found for size {$size} from {$this->systemName} to {$targetSystem}");
            }

            return $convertedSize;
        } catch (Exception $e) {
            error_log("Size conversion error: " . $e->getMessage());
            return $size; // Return original size if conversion fails
        }
    }

    public function getSystemName(): string {
        return $this->systemName;
    }

    public function getSystemId(): int {
        return $this->systemId;
    }

    public static function getSystemForCategory(int $categoryId): ?SizeSystemAdapter {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                SELECT ss.id, ss.name 
                FROM size_systems ss
                JOIN category_size_system css ON ss.id = css.size_system_id
                WHERE css.category_id = ?
            ");
            $stmt->execute([$categoryId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return new self($result['id'], $result['name']);
            }
            return null;
        } catch (Exception $e) {
            error_log("Error getting size system for category: " . $e->getMessage());
            return null;
        }
    }
} 