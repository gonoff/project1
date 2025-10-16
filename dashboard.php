<?php
$page_title = 'Dashboard';
require_once __DIR__ . '/config/config.php';
require_once MODULES_PATH . '/users/User.php';
require_once MODULES_PATH . '/roles/Role.php';
require_once MODULES_PATH . '/modules/Module.php';

// Require authentication
if (!isLoggedIn()) {
    redirect(BASE_URL . '/login.php');
}

$userId = $_SESSION['user_id'];
$user = new User();
$roleManager = new Role();
$moduleManager = new Module();

// Get user information
$userRoles = $user->getRoles($userId);
$userPermissions = $user->getPermissions($userId);
$accessibleModules = $moduleManager->getAccessibleByUser($userId);

require_once __DIR__ . '/includes/header.php';
?>

<main class="container mt-4">
    <?php displayFlash(); ?>

    <div class="row">
        <div class="col-12">
            <h1>
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </h1>
            <p class="lead">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        </div>
    </div>

    <div class="row mt-4">
        <!-- User Info Card -->
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-person-circle"></i>
                        Your Profile
                    </h5>
                    <p class="card-text">
                        <strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?><br>
                        <strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Roles Card -->
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-shield-check"></i>
                        Your Roles
                    </h5>
                    <?php if (!empty($userRoles)): ?>
                        <div class="card-text">
                            <?php foreach ($userRoles as $role): ?>
                                <span class="badge badge-primary mb-2">
                                    <?php echo htmlspecialchars($role['name']); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="card-text text-secondary">No roles assigned yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Accessible Modules Card -->
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-grid"></i>
                        Accessible Modules
                    </h5>
                    <p class="card-text">
                        <strong><?php echo count($accessibleModules); ?></strong> modules available
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Accessible Modules Grid -->
    <?php if (!empty($accessibleModules)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <h3>Your Modules</h3>
            </div>
            <?php foreach ($accessibleModules as $module): ?>
                <div class="col-3 mt-3">
                    <div class="card" style="cursor: pointer;" onclick="alert('Module: <?php echo htmlspecialchars($module['name']); ?>')">
                        <div class="card-body text-center">
                            <i class="<?php echo htmlspecialchars($module['icon']); ?>" style="font-size: 3rem; color: var(--primary-color);"></i>
                            <h5 class="mt-3"><?php echo htmlspecialchars($module['display_name']); ?></h5>
                            <p class="text-secondary" style="font-size: 0.875rem;">
                                <?php echo htmlspecialchars($module['description']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    No modules accessible. Please contact an administrator to assign you a role.
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Permissions Details -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-key"></i>
                        Your Permissions
                    </h5>
                    <?php if (!empty($userPermissions)): ?>
                        <div style="max-height: 300px; overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>Permission</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($userPermissions as $permission): ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?php echo htmlspecialchars($permission['module_name']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($permission['name']); ?></td>
                                            <td class="text-secondary"><?php echo htmlspecialchars($permission['description']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="card-text text-secondary">No permissions assigned.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
