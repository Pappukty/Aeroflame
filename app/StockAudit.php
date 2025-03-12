<?php

// Include Database Connection
include_once './class/databaseConn.php'; 

class StockAudit {
    private $db;

    // Constructor to initialize database connection
    public function __construct($DatabaseCo) {
        $this->db = $DatabaseCo->dbLink; // Assign database connection
    }

    // ✅ Fetch all stock audits with error handling
    public function getAllAudits() {
        try {
            $stmt = $this->db->prepare("SELECT sa.*, p.product_name AS spare_name 
                                        FROM stock_audits sa 
                                        JOIN products p ON sa.spare_part_id = p.id 
                                        ORDER BY sa.audit_date DESC");
            if (!$stmt) {
                throw new Exception("SQL Prepare Error: " . $this->db->error);
            }

            if (!$stmt->execute()) {
                throw new Exception("SQL Execution Error: " . $stmt->error);
            }

            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);

        } catch (Exception $e) {
            return "Error fetching stock audits: " . $e->getMessage();
        }
    }

    // ✅ Fetch a single stock audit with error handling
    public function getAuditById($id) {
        try {
            if (!is_numeric($id)) {
                throw new Exception("Invalid ID format.");
            }

            $stmt = $this->db->prepare("SELECT * FROM stock_audits WHERE id = ?");
            if (!$stmt) {
                throw new Exception("SQL Prepare Error: " . $this->db->error);
            }

            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception("SQL Execution Error: " . $stmt->error);
            }

            $result = $stmt->get_result();
            return $result->fetch_assoc();

        } catch (Exception $e) {
            return "Error fetching stock audit: " . $e->getMessage();
        }
    }

    // ✅ Insert a new stock audit with error handling
    public function addAudit($spare_part_id, $actual_stock, $recorded_stock) {
        try {
            if (!is_numeric($spare_part_id) || !is_numeric($actual_stock) || !is_numeric($recorded_stock)) {
                throw new Exception("Invalid input data. Please provide numeric values.");
            }

            $variance = $actual_stock - $recorded_stock; // Calculate variance

            // Check if spare_part_id exists
            $checkStmt = $this->db->prepare("SELECT id FROM products WHERE id = ?");
            $checkStmt->bind_param("i", $spare_part_id);
            $checkStmt->execute();
            $checkStmt->store_result();
            if ($checkStmt->num_rows == 0) {
                throw new Exception("Error: Spare part ID does not exist.");
            }

            // Insert new stock audit
            $stmt = $this->db->prepare("INSERT INTO stock_audits (spare_part_id, actual_stock, recorded_stock, variance, audit_date) 
                                        VALUES (?, ?, ?, ?, NOW())");
            if (!$stmt) {
                throw new Exception("SQL Prepare Error: " . $this->db->error);
            }

            $stmt->bind_param("iiii", $spare_part_id, $actual_stock, $recorded_stock, $variance);
            if (!$stmt->execute()) {
                throw new Exception("SQL Execution Error: " . $stmt->error);
            }

            return "Stock audit added successfully!";

        } catch (Exception $e) {
            return "Error inserting stock audit: " . $e->getMessage();
        }
    }

    // ✅ Update an existing stock audit with error handling
    public function updateAudit($id, $spare_part_id, $actual_stock, $recorded_stock) {
        try {
            if (!is_numeric($id) || !is_numeric($spare_part_id) || !is_numeric($actual_stock) || !is_numeric($recorded_stock)) {
                throw new Exception("Invalid input data. Please provide numeric values.");
            }

            $variance = $actual_stock - $recorded_stock; // Recalculate variance

            // Check if ID exists
            $checkStmt = $this->db->prepare("SELECT id FROM stock_audits WHERE id = ?");
            $checkStmt->bind_param("i", $id);
            $checkStmt->execute();
            $checkStmt->store_result();
            if ($checkStmt->num_rows == 0) {
                throw new Exception("Error: Stock audit ID does not exist.");
            }

            // Update stock audit
            $stmt = $this->db->prepare("UPDATE stock_audits 
                                        SET spare_part_id = ?, actual_stock = ?, recorded_stock = ?, variance = ?, audit_date = NOW() 
                                        WHERE id = ?");
            if (!$stmt) {
                throw new Exception("SQL Prepare Error: " . $this->db->error);
            }

            $stmt->bind_param("iiiii", $spare_part_id, $actual_stock, $recorded_stock, $variance, $id);
            if (!$stmt->execute()) {
                throw new Exception("SQL Execution Error: " . $stmt->error);
            }

            return "Stock audit updated successfully!";

        } catch (Exception $e) {
            return "Error updating stock audit: " . $e->getMessage();
        }
    }

    // ✅ Delete a stock audit with error handling
    public function deleteAudit($id) {
        try {
            if (!is_numeric($id)) {
                throw new Exception("Invalid ID format.");
            }

            // Check if ID exists
            $checkStmt = $this->db->prepare("SELECT id FROM stock_audits WHERE id = ?");
            $checkStmt->bind_param("i", $id);
            $checkStmt->execute();
            $checkStmt->store_result();
            if ($checkStmt->num_rows == 0) {
                throw new Exception("Error: Stock audit ID does not exist.");
            }

            // Delete stock audit
            $stmt = $this->db->prepare("DELETE FROM stock_audits WHERE id = ?");
            if (!$stmt) {
                throw new Exception("SQL Prepare Error: " . $this->db->error);
            }

            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception("SQL Execution Error: " . $stmt->error);
            }

            return "Stock audit deleted successfully!";

        } catch (Exception $e) {
            return "Error deleting stock audit: " . $e->getMessage();
        }
    }
}

?>
