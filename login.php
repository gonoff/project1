<?php
$page_title = 'Login';
require_once __DIR__ . '/config/config.php';
require_once MODULES_PATH . '/users/User.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(BASE_URL . '/dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($email)) {
        $errors[] = 'Email is required';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    // Authenticate user
    if (empty($errors)) {
        $user = new User();
        $authenticatedUser = $user->verifyPassword($email, $password);

        if ($authenticatedUser) {
            // Check if user is active
            if (isset($authenticatedUser['is_active']) && !$authenticatedUser['is_active']) {
                $errors[] = 'Your account has been deactivated. Please contact support.';
            } else {
                // Set session
                $_SESSION['user_id'] = $authenticatedUser['id'];
                $_SESSION['username'] = $authenticatedUser['username'];
                $_SESSION['email'] = $authenticatedUser['email'];

                setFlash('success', 'Welcome back, ' . $authenticatedUser['username'] . '!');
                redirect(BASE_URL . '/dashboard.php');
            }
        } else {
            $errors[] = 'Invalid email or password';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="container mt-5">
    <div class="row">
        <div class="col-12" style="max-width: 500px; margin: 0 auto;">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Login
                    </h2>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-error">
                            <i class="bi bi-x-circle-fill"></i>
                            <div>
                                <?php foreach ($errors as $error): ?>
                                    <div><?php echo htmlspecialchars($error); ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                required
                                autofocus
                            >
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Password
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-full">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <p class="text-secondary">
                            Don't have an account?
                            <a href="<?php echo BASE_URL; ?>/register.php" style="color: var(--primary-color); font-weight: bold;">
                                Register here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
