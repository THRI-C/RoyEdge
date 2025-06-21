<?php include 'include/sidebar.php' ?>
    <div class="main-content">
        <?php include 'include/navbar.php' ?>
        <h4 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 18px;">Your Orders</h4>
        <table class='table' style='background: #fff; border-radius: 8px; overflow: hidden;'>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>12345</td>
                    <td>Shipped</td>
                    <td><button class='btn btn-secondary'>View</button></td>
                </tr>
                <tr>
                    <td>12346</td>
                    <td>Pending</td>
                    <td><button class='btn btn-secondary'>View</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>