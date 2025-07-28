<?php
class PackageManager {
    private $pdo;
    private $manager_no; // Will be set from settings

    public function __construct($pdo, $manager_no = null) {
        $this->pdo = $pdo;
        $this->manager_no = $manager_no;
    }

    /**
     * Set the manager number.
     *
     * @param string $manager_no The manager number to be set.
    */
    public function setManagerNumber($manager_no) {
        $this->manager_no = $manager_no;
    }

    public function getPackagesByStatus($user_id, $status)
    {
        $stmt = $this->pdo->prepare("
        SELECT * FROM packages 
        WHERE user_id = ? AND status = ?
        ORDER BY created_at DESC
    ");
        $stmt->execute([$user_id, $status]);
        return $stmt->fetchAll();
    }

    public function getRecentPackages($user_id, $limit = 5)
    {
        $stmt = $this->pdo->prepare("
        SELECT * FROM packages 
        WHERE user_id = ? 
        ORDER BY created_at DESC
        LIMIT ?
    ");
        $stmt->execute([$user_id, $limit]);
        return $stmt->fetchAll();
    }
    
    public function addPackage($data, $user_id) {
        $required = ['product_name', 'tracking_number', 'quantity', 
                    'shipment_mode', 'goods_nature', 'warehouse'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        $stmt = $this->pdo->prepare("INSERT INTO packages 
            (user_id, product_name, tracking_number, quantity, 
             shipment_mode, goods_nature, warehouse)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $user_id,
            $data['product_name'], // No double escaping
            $data['tracking_number'],
            $data['quantity'],
            $data['shipment_mode'],
            $data['goods_nature'],
            $data['warehouse']
        ]);
    }

    public function getPackages($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM packages WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function deletePackage($package_id, $user_id) {
        $stmt = $this->pdo->prepare("DELETE FROM packages WHERE id = ? AND user_id = ?");
        return $stmt->execute([$package_id, $user_id]);
    }

    public function getPackagesForConsolidation($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM packages 
            WHERE user_id = ? 
            AND (consolidated_id IS NULL OR consolidated_id = 0)
            ORDER BY created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function createConsolidatedOrder($user_id, $package_ids, $address, $phone, $package_names = null) {
        // Validate minimum 2 packages required
        if (count($package_ids) < 2) {
            throw new Exception("You must select at least 2 packages for consolidation");
        }

        if (!$this->manager_no) {
            throw new Exception("Manager number not configured");
        }

        // Verify all packages belong to user
        $placeholders = implode(',', array_fill(0, count($package_ids), '?'));
        $stmt = $this->pdo->prepare("
            SELECT id, product_name FROM packages 
            WHERE id IN ($placeholders) AND user_id = ?
        ");
        $stmt->execute(array_merge($package_ids, [$user_id]));
        $packages = $stmt->fetchAll();
        
        if (count($packages) != count($package_ids)) {
            throw new Exception("Some packages don't belong to you");
        }

        // Use provided package names or get from query results
        if ($package_names === null) {
            $package_names = array_column($packages, 'product_name');
        }
        $consolidated_name = implode(' + ', $package_names);

        // Generate master tracking number
        $master_tracking = 'CON-' . strtoupper(uniqid());

        // Create consolidated order
        $this->pdo->beginTransaction();
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO consolidated_orders 
                (user_id, master_tracking_number, name, manager_no, address, phone)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id,
                $master_tracking,
                $consolidated_name,
                $this->manager_no,
                $address,
                $phone
            ]);
            
            $consolidated_id = $this->pdo->lastInsertId();

            // Update packages with consolidated_id
            $stmt = $this->pdo->prepare("
                UPDATE packages SET consolidated_id = ? 
                WHERE id IN ($placeholders)
            ");
            $stmt->execute(array_merge([$consolidated_id], $package_ids));
            
            $this->pdo->commit();
            return $master_tracking;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }


    public function getConsolidatedPackages($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   co.id as co_id,
                   co.master_tracking_number, 
                   co.name as consolidated_name, 
                   co.manager_no, 
                   co.address, 
                   co.phone, 
                   co.created_at as consolidated_date,
                   co.status
            FROM packages p
            JOIN consolidated_orders co ON p.consolidated_id = co.id
            WHERE p.user_id = ?
            ORDER BY co.created_at DESC, p.created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}