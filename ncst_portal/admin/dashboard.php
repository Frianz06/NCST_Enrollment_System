<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../ncst_login.php');
    exit;
}
require_once '../../db.php';
$page = $_GET['page'] ?? 'users';
function active_link($p, $page) { return $p === $page ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | NCST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="admin.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../../faviconn.ico">
</head>
<body>
    <!-- Sidebar: visible only on md and up -->
    <div class="sidebar d-none d-md-flex flex-column align-items-center">
        <img src="../../images/ncst-logo.png" alt="NCST Logo" class="logo">
        <h5 class="mb-4">NCST Admin</h5>
        <nav class="nav flex-column w-100">
            <a class="nav-link <?php echo active_link('users', $page); ?>" href="?page=users">User Management</a>
        </nav>
    </div>
    <!-- Hamburger Button for Mobile Only (top left) -->
    <button class="hamburger-btn d-md-none position-fixed top-0 start-0 m-3 z-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
      <div class="menu-icon">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </button>
    <!-- Offcanvas Sidebar for Mobile Only -->
    <div class="offcanvas offcanvas-start offcanvas-ncst d-md-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">NCST Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="text-center mb-4">
          <div class="logo d-inline-block">
            <img src="../../images/ncst-logo.png" alt="NCST Logo" style="max-width: 60px;">
          </div>
        </div>
        <nav class="nav flex-column">
          <a class="nav-link <?php echo active_link('users', $page); ?>" href="?page=users">User Management</a>
        </nav>
      </div>
    </div>
    <div class="topbar d-flex align-items-center justify-content-end">
        <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="../logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
    <div class="main-content">
        <?php if ($page === 'users'): ?>
            <h2 class="welcome mb-4">User Management</h2>
            <?php
            // Handle Add User form submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
                $name = trim($_POST['name']);
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $role = $_POST['role'];
                if ($name && $username && $password && $role) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare('INSERT INTO users (name, username, password, role) VALUES (?, ?, ?, ?)');
                    if ($stmt) {
                        $stmt->bind_param('ssss', $name, $username, $hashed_password, $role);
                        $stmt->execute();
                        echo '<div class="alert alert-success">User added successfully!</div>';
                    } else {
                        echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($conn->error) . '</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">All fields are required.</div>';
                }
            }
            // Handle Edit User form submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
                $edit_id = $_POST['edit_id'];
                $edit_name = trim($_POST['edit_name']);
                $edit_username = trim($_POST['edit_username']);
                $edit_password = $_POST['edit_password'];
                $edit_role = $_POST['edit_role'];
                if ($edit_name && $edit_username && $edit_role) {
                    if ($edit_password) {
                        $hashed_edit_password = password_hash($edit_password, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare('UPDATE users SET name=?, username=?, password=?, role=? WHERE id=?');
                        if ($stmt) {
                            $stmt->bind_param('ssssi', $edit_name, $edit_username, $hashed_edit_password, $edit_role, $edit_id);
                        } else {
                            echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($conn->error) . '</div>';
                        }
                    } else {
                        $stmt = $conn->prepare('UPDATE users SET name=?, username=?, role=? WHERE id=?');
                        if ($stmt) {
                            $stmt->bind_param('sssi', $edit_name, $edit_username, $edit_role, $edit_id);
                        } else {
                            echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($conn->error) . '</div>';
                        }
                    }
                    if ($stmt) {
                        $stmt->execute();
                        echo '<div class="alert alert-success">User updated successfully!</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">Name, username, and role are required.</div>';
                }
            }
            // Handle Delete User
            if (isset($_POST['delete_user_id'])) {
                $delete_id = $_POST['delete_user_id'];
                $stmt = $conn->prepare('DELETE FROM users WHERE id=?');
                $stmt->bind_param('i', $delete_id);
                $stmt->execute();
                echo '<div class="alert alert-success">User deleted successfully!</div>';
            }
            // Fetch users except students
            $users = $conn->query("SELECT id, name, username, role FROM users WHERE role != 'student' ORDER BY id ASC");
            ?>
            <div class="mb-3 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
            </div>
            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($row['role'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $row['id']; ?>">Edit</button>
                                    <form method="post" action="?page=users" style="display:inline-block;">
                                        <input type="hidden" name="delete_user_id" value="<?php echo $row['id']; ?>">
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?php echo $row['id']; ?>">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <!-- Edit User Modal -->
                            <div class="modal fade" id="editUserModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post" action="?page=users">
                                            <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUserModalLabel<?php echo $row['id']; ?>">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="edit_name<?php echo $row['id']; ?>" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="edit_name<?php echo $row['id']; ?>" name="edit_name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_username<?php echo $row['id']; ?>" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="edit_username<?php echo $row['id']; ?>" name="edit_username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_password<?php echo $row['id']; ?>" class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                                                    <input type="password" class="form-control" id="edit_password<?php echo $row['id']; ?>" name="edit_password" placeholder="Enter new password">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_role<?php echo $row['id']; ?>" class="form-label">Role</label>
                                                    <select class="form-select" id="edit_role<?php echo $row['id']; ?>" name="edit_role" required>
                                                        <option value="admin" <?php if ($row['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                                                        <option value="admission" <?php if ($row['role'] === 'admission') echo 'selected'; ?>>Admission Officer</option>
                                                        <option value="registration" <?php if ($row['role'] === 'registration') echo 'selected'; ?>>Registration Officer</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary" name="edit_user">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete User Modal -->
                            <div class="modal fade" id="deleteUserModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteUserModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="post" action="?page=users">
                                            <input type="hidden" name="delete_user_id" value="<?php echo $row['id']; ?>">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteUserModalLabel<?php echo $row['id']; ?>">Confirm Delete</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete user <strong><?php echo htmlspecialchars($row['name']); ?></strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Add User Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="?page=users">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Select role</option>
                                        <option value="admin">Admin</option>
                                        <option value="admission">Admission Officer</option>
                                        <option value="registration">Registration Officer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" name="add_user">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 