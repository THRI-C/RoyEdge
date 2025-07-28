<?php
// settings.php - Complete profile editing with all fields
require_once 'functions/auth.php';
require_once 'functions/config.php';

$auth = new Auth($pdo);

// Check if user is logged in
if (!$auth->checkSession()) {
    header('Location: ../login.php?message=Please login to access this page');
    exit;
}

$user = getCurrentUser($pdo);
$message = '';
$messageType = 'error';

// Get full user profile data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$profile = $stmt->fetch();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Form submitted via POST");
    error_log("Available POST keys: " . implode(', ', array_keys($_POST)));

    // Handle profile update
    if (isset($_POST['update_profile'])) {
        error_log("Profile update attempt for user ID: " . $_SESSION['user_id']);
        error_log("POST data: " . print_r($_POST, true));
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $lga = trim($_POST['lga'] ?? '');
        $postalCode = trim($_POST['postal_code'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $dateOfBirth = $_POST['date_of_birth'] ?? null;

        // Validate required fields
        if (empty($username) || empty($email)) {
            $message = 'Username and email are required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Invalid email format';
        } else {
            // Check if username/email already exists for other users
            $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $stmt->execute([$username, $email, $_SESSION['user_id']]);

            if ($stmt->fetch()) {
                $message = 'Username or email already exists';
            } else {
                // Handle file upload
                $profileImage = $profile['profile_image']; // Keep existing image by default

                if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = handleImageUpload($_FILES['profile_image'], $_SESSION['user_id']);

                    if ($uploadResult['success']) {
                        // Delete old image if exists
                        if ($profile['profile_image'] && file_exists('uploads/profiles/' . $profile['profile_image'])) {
                            unlink('uploads/profiles/' . $profile['profile_image']);
                        }
                        $profileImage = $uploadResult['filename'];
                    } else {
                        $message = $uploadResult['message'];
                        $messageType = 'error';
                    }
                }

                if (empty($message)) {
                    try {
                        // First check if all columns exist by doing a describe
                        $checkStmt = $pdo->prepare("DESCRIBE users");
                        $checkStmt->execute();
                        $columns = $checkStmt->fetchAll(PDO::FETCH_COLUMN);
                        error_log("Available columns in users table: " . implode(', ', $columns));
                        
                        // Update profile with all fields
                        $stmt = $pdo->prepare("
                            UPDATE users SET 
                            first_name = ?, last_name = ?, username = ?, email = ?, 
                            phone = ?, address = ?, city = ?, state = ?, lga = ?,
                            postal_code = ?, country = ?, profile_image = ?, 
                            date_of_birth = ?
                            WHERE id = ?
                        ");

                        $result = $stmt->execute([
                            $firstName,
                            $lastName,
                            $username,
                            $email,
                            $phone,
                            $address,
                            $city,
                            $state,
                            $lga,
                            $postalCode,
                            $country,
                            $profileImage,
                            $dateOfBirth,
                            $_SESSION['user_id']
                        ]);
                        
                        error_log("SQL executed, affected rows: " . $stmt->rowCount());

                    if ($result) {
                        // Update session data
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $email;

                        $message = 'Profile updated successfully!';
                        $messageType = 'success';
                        error_log("Profile updated successfully for user ID: " . $_SESSION['user_id']);

                        // Refresh profile data
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                        $stmt->execute([$_SESSION['user_id']]);
                        $profile = $stmt->fetch();
                    } else {
                        $message = 'Failed to update profile. Database error.';
                        error_log("Database update failed for user ID: " . $_SESSION['user_id']);
                        error_log("SQL error info: " . print_r($stmt->errorInfo(), true));
                    }
                    } catch (PDOException $e) {
                        $message = 'Database error: ' . $e->getMessage();
                        error_log("PDO Exception in profile update: " . $e->getMessage());
                    }
                }
            }
        }
    }

    // Handle password change
    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $message = 'New passwords do not match';
        } else {
            $result = $auth->changePassword($_SESSION['user_id'], $currentPassword, $newPassword);
            $message = $result['message'];
            $messageType = $result['success'] ? 'success' : 'error';
        }
    }
}

// Function to handle image upload
function handleImageUpload($file, $userId)
{
    $uploadDir = 'uploads/profiles/';

    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return ['success' => false, 'message' => 'Failed to create upload directory'];
        }
    }

    // Validate file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB as specified in the form

    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed'];
    }

    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large. Maximum size is 1MB'];
    }

    // Generate unique filename
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to upload file'];
    }
}

// Get full name for display
function getFullName($profile)
{
    $parts = array_filter([
        $profile['first_name'] ?? '',
        $profile['last_name'] ?? ''
    ]);
    return !empty($parts) ? implode(' ', $parts) : $profile['username'];
}
?>


<?php include 'include/sidebar.php' ?>

<main class="main-content" id="main-content">
    <?php include 'include/navbar.php' ?>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4">Profile Information</h2>

                    <?php if ($message): ?>
                        <div class="message <?php echo $messageType; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Profile Information Section -->
                    <div class="form-section">
                        <form method="POST" enctype="multipart/form-data">
                            <!-- Profile Image -->
                            <div class="profile-image-section">
                                <?php if ($profile['profile_image'] && file_exists('uploads/profiles/' . $profile['profile_image'])): ?>
                                    <img src="uploads/profiles/<?php echo htmlspecialchars($profile['profile_image']); ?>"
                                        alt="Profile Image" class="profile-image-preview" id="imagePreview">
                                <?php else: ?>
                                    <div class="default-avatar" id="defaultAvatar">
                                        <i class="ri-user-fill"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3 mt-3">
                                    <label for="profile_image" class="form-label">Profile Image (Max. File Size: 5.0 Mb)</label>
                                    <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
                                </div>
                            </div>

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="required">*</span></label>
                                <input type="text" name="name" id="name"
                                    value="<?php echo htmlspecialchars(getFullName($profile)); ?>"
                                    required class="form-control" readonly>
                                <small class="text-muted">This is automatically generated from first and last name</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username <span class="required">*</span></label>
                                <input type="text" name="username" id="username"
                                    value="<?php echo htmlspecialchars($profile['username'] ?? ''); ?>"
                                    required class="form-control">
                            </div>

                            <!-- Email  -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email"
                                    value="<?php echo htmlspecialchars($profile['email']); ?>"
                                    class="form-control">
                            </div>

                            <!-- Phone -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" name="phone" id="phone"
                                    value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>"
                                    class="form-control">
                            </div>

                            <!-- Date of Birth -->
                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth"
                                    value="<?php echo $profile['date_of_birth'] ?? ''; ?>"
                                    class="form-control">
                            </div>

                            <!-- Address Line -->
                            <div class="mb-3">
                                <label for="address_line" class="form-label">Address Line</label>
                                <input type="text" name="address" id="address_line"
                                    value="<?php echo htmlspecialchars($profile['address'] ?? ''); ?>"
                                    class="form-control">
                            </div>

                            <!-- City -->
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" name="city" id="city"
                                    value="<?php echo htmlspecialchars($profile['city'] ?? ''); ?>"
                                    class="form-control">
                            </div>

                            <!-- State -->
                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" name="state" id="state"
                                    value="<?php echo htmlspecialchars($profile['state'] ?? ''); ?>"
                                    class="form-control">
                            </div>

                            <!-- LGA -->
                            <div class="mb-3">
                                <label for="lga" class="form-label">LGA</label>
                                <input type="text" name="lga" id="lga"
                                    value="<?php echo htmlspecialchars($profile['lga'] ?? ''); ?>"
                                    class="form-control">
                            </div>

                            <!-- Postal Code -->
                            <div class="mb-3">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" id="postal_code"
                                    value="<?php echo htmlspecialchars($profile['postal_code'] ?? ''); ?>"
                                    class="form-control">
                            </div>

                            <!-- Country -->
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" name="country" id="country"
                                    value="<?php echo htmlspecialchars($profile['country'] ?? ''); ?>"
                                    class="form-control">
                            </div>

                            <button type="submit" name="update_profile" class="btn btn-secondary">
                                Update Profile
                            </button>
                        </form>
                    </div>

                    <!-- Change Password Section -->
                    <div class="form-section">
                        <h4 class="section-title">Change Password</h4>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password"
                                            name="current_password" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password"
                                            name="new_password" required minlength="6">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirm_password"
                                            name="confirm_password" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="change_password" class="btn btn-warning">
                                <i class="ri-lock-password-line"></i> Change Password
                            </button>
                        </form>
                    </div>

                    <!-- Back to Dashboard -->
                    <div class="text-center">
                        <a href="userdashboard.php" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>

<script src="../asset/js/jquery-3.7.1.min.js"></script>
<script src="../asset/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

<script>
    // Update the name field when first/last name changes
    function updateFullName() {
        const firstName = document.getElementById('first_name').value;
        const lastName = document.getElementById('last_name').value;
        const fullName = (firstName + ' ' + lastName).trim() || document.getElementById('username').value;
        document.getElementById('name').value = fullName;
    }

    // Add event listeners
    document.getElementById('first_name').addEventListener('input', updateFullName);
    document.getElementById('last_name').addEventListener('input', updateFullName);

    // Add form submission debugging
    document.querySelector('form[method="POST"]').addEventListener('submit', function(e) {
        console.log('Form is being submitted');
        alert('Profile update form submitted!');
        // Don't prevent default - let it submit
    });

    // Image preview functionality
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                const defaultAvatar = document.getElementById('defaultAvatar');
                
                if (preview) {
                    preview.src = e.target.result;
                } else if (defaultAvatar) {
                    defaultAvatar.style.display = 'none';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'profile-image-preview';
                    img.id = 'imagePreview';
                    defaultAvatar.parentNode.insertBefore(img, defaultAvatar);
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
</body>

</html>