                            <div class="modal-header" style="background: #fca311; color: #22223b; border-bottom: none;">
                                <h5 class="modal-title" style="font-weight:700;">
                                    <i class="ri-archive-2-line" style="margin-right:8px;"></i>Order Details 
                                    <span class="badge" style="margin-left:8px; font-size:0.9em;"> 
                                        <?= $modalType === 'consolidated' ? 'Consolidated' : 'Individual' ?> 
                                    </span>
                                </h5>
                                <a href="?" class="close" style="color:#22223b; opacity:0.7; font-size:1.5rem; text-decoration:none;">&times;</a>
                            </div>
                            <div class="modal-body" style="background:#fff;">
                                <div class="card" style="border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,0.04); border-top:4px solid #fca311;">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-2">
                                                <div class="stat-label">Order ID</div>
                                                <div class="stat-value" style="font-size:1.2em;"> 
                                                    <?= $modalType === 'consolidated' ? 'C' . $modalOrder['id'] : $modalOrder['id'] ?> 
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="stat-label">Customer</div>
                                                <div class="stat-value" style="font-size:1.1em;"> 
                                                    <?= htmlspecialchars($modalOrder['username']) ?> 
                                                    <span class="badge" style="margin-left:6px;"> 
                                                        <?= htmlspecialchars($modalOrder['email']) ?> 
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="stat-label">Description</div>
                                                <div class="stat-value"> 
                                                    <?= htmlspecialchars($modalType === 'consolidated' ? $modalOrder['name'] : $modalOrder['product_name']) ?> 
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="stat-label">Status</div>
                                                <span class="badge" style="font-size:1em; background:#fca311; color:#22223b;"> 
                                                    <?= ucfirst($modalOrder['status']) ?> 
                                                </span>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="stat-label">Tracking #</div>
                                                <div class="stat-value"> 
                                                    <?= htmlspecialchars($modalType === 'consolidated' ? $modalOrder['master_tracking_number'] : $modalOrder['tracking_number']) ?> 
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="stat-label">Warehouse</div>
                                                <div class="stat-value"> 
                                                    <?= htmlspecialchars($modalType === 'consolidated' ? ($modalOrder['manager_no'] ?? '-') : $modalOrder['warehouse']) ?> 
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="stat-label">Date Created</div>
                                                <div class="stat-value"> 
                                                    <?= date('M d, Y', strtotime($modalOrder['created_at'])) ?> 
                                                </div>
                                            </div>
                                            <?php if ($modalType !== 'consolidated'): ?>
                                                <div class="col-md-6 mb-2">
                                                    <div class="stat-label">ETA</div>
                                                    <div class="stat-value"> 
                                                        <?= htmlspecialchars($modalOrder['eta'] ?? '-') ?> 
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <hr style="border-top:1px solid #fca311; margin:18px 0;">
                                        
                                        <div class="row">
                                            <!-- Status Update Form -->
                                            <div class="col-md-6 mb-3">
                                                <form method="POST">
                                                    <input type="hidden" name="update_status_id" value="<?= $modalOrder['id'] ?>">
                                                    <input type="hidden" name="update_status_type" value="<?= $modalType ?>">
                                                    <div class="form-group mb-2">
                                                        <label class="stat-label">Change Status</label>
                                                        <select name="update_status_value" class="form-control">
                                                            <option value="pending" <?= $modalOrder['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                            <option value="in_transit" <?= $modalOrder['status'] === 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                                                            <option value="delivered" <?= $modalOrder['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                                            <option value="cancelled" <?= $modalOrder['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary btn-sm" style="background:#fca311; color:#22223b; border:none;">
                                                        Update Status
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <!-- Warehouse Update Form (Individual packages only) -->
                                            <?php if ($modalType !== 'consolidated'): ?>
                                                <div class="col-md-6 mb-3">
                                                    <form method="post">
                                                        <input type="hidden" name="update_warehouse_id" value="<?= $modalOrder['id'] ?>">
                                                        <div class="form-group mb-2">
                                                            <label class="stat-label">Assign Warehouse</label>
                                                            <select name="update_warehouse_value" class="form-control">
                                                                <?php foreach ($warehouses as $wh): ?>
                                                                    <option value="<?= htmlspecialchars($wh) ?>" <?= ($modalOrder['warehouse'] === $wh) ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($wh) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn btn-secondary btn-sm" style="border:1px solid #fca311; color:#fca311; background:#fff;">
                                                            Update Warehouse
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Delete Form -->
                                            <div class="col-md-12 mt-3">
                                                <form method="post" onsubmit="return confirm('Are you sure you want to delete this order?')">
                                                    <input type="hidden" name="delete_order_id" value="<?= $modalOrder['id'] ?>">
                                                    <input type="hidden" name="delete_order_type" value="<?= $modalType ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" style="background:#d90429; border:none;">
                                                        Delete Order
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <!-- Package List for Consolidated Orders -->
                                            <?php if ($modalType === 'consolidated'): ?>
                                                <div class="col-md-12 mt-4">
                                                    <h6 style="font-weight:600; margin-bottom:16px;">
                                                        <i class="ri-package-line" style="margin-right:8px;"></i>Packages in this Order
                                                    </h6>
                                                    <?php
                                                    $stmt = $pdo->prepare('SELECT p.*, u.username FROM packages p JOIN users u ON p.user_id = u.id WHERE p.consolidated_id = ?');
                                                    $stmt->execute([$modalOrder['id']]);
                                                    $packages = $stmt->fetchAll();
                                                    ?>
                                                    
                                                    <?php if ($packages): ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Customer</th>
                                                                        <th>Description</th>
                                                                        <th>Status</th>
                                                                        <th>Tracking</th>
                                                                        <th>Warehouse</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($packages as $package): ?>
                                                                        <tr>
                                                                            <td><?= $package['id'] ?></td>
                                                                            <td><?= htmlspecialchars($package['username']) ?></td>
                                                                            <td><?= htmlspecialchars($package['product_name']) ?></td>
                                                                            <td>
                                                                                <span class="badge bg-<?= $package['status'] === 'delivered' ? 'success' : ($package['status'] === 'in_transit' ? 'warning' : ($package['status'] === 'cancelled' ? 'danger' : 'secondary')) ?>">
                                                                                    <?= ucfirst($package['status']) ?>
                                                                                </span>
                                                                            </td>
                                                                            <td><?= htmlspecialchars($package['tracking_number']) ?></td>
                                                                            <td><?= htmlspecialchars($package['warehouse']) ?></td>
                                                                            <td>
                                                                                <a href="?modal_id=<?= $package['id'] ?>&modal_type=individual" class="btn btn-sm btn-outline-primary">View</a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="alert alert-warning">No packages found in this consolidated order.</div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        <?php endif; ?>
        
        <?php include 'include/footer.php'; ?>
    </main>

    <script>
    // Select all functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Show/hide bulk action fields based on selection
    document.querySelector('select[name="bulk_action"]').addEventListener('change', function() {
        const statusSelect = document.getElementById('status-select');
        const warehouseSelect = document.getElementById('warehouse-select');
        
        // Hide all
        statusSelect.style.display = 'none';
        warehouseSelect.style.display = 'none';
        
        // Show relevant field
        if (this.value === 'update_status') {
            statusSelect.style.display = 'block';
        } else if (this.value === 'update_warehouse') {
            warehouseSelect.style.display = 'block';
        }
    });

    // Confirm bulk actions
    function confirmBulkAction() {
        const selectedCheckboxes = document.querySelectorAll('.order-checkbox:checked');
        const bulkAction = document.querySelector('select[name="bulk_action"]').value;
        
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one order.');
            return false;
        }
        
        if (!bulkAction) {
            alert('Please select a bulk action.');
            return false;
        }
        
        let message = '';
        switch (bulkAction) {
            case 'update_status':
                const status = document.querySelector('select[name="bulk_status_value"]').value;
                message = `Are you sure you want to change the status of ${selectedCheckboxes.length} orders to "${status}"?`;
                break;
            case 'update_warehouse':
                const warehouse = document.querySelector('select[name="bulk_warehouse_value"]').value;
                message = `Are you sure you want to assign ${selectedCheckboxes.length} orders to warehouse "${warehouse}"?`;
                break;
            case 'delete':
                message = `Are you sure you want to delete ${selectedCheckboxes.length} orders? This action cannot be undone.`;
                break;
        }
        
        return confirm(message);
    }

    // Close modal when clicking backdrop
    <?php if ($showModal): ?>
    document.querySelector('.modal-backdrop').addEventListener('click', function() {
        window.location.href = '?';
    });
    <?php endif; ?>
    </script>