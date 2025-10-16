<?php
$page_title = 'Register';
require_once __DIR__ . '/config/config.php';
require_once MODULES_PATH . '/users/User.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(BASE_URL . '/dashboard.php');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters';
    } elseif (strlen($username) > 50) {
        $errors[] = 'Username must not exceed 50 characters';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    // Check if user already exists
    if (empty($errors)) {
        $user = new User();

        if ($user->emailExists($email)) {
            $errors[] = 'Email already registered';
        }

        if ($user->usernameExists($username)) {
            $errors[] = 'Username already taken';
        }
    }

    // Create user if no errors
    if (empty($errors)) {
        $user = new User();

        if ($user->create($username, $email, $password)) {
            $success = true;
            setFlash('success', 'Registration successful! You can now log in.');
        } else {
            $errors[] = 'Registration failed. Please try again.';
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
                        <i class="bi bi-person-plus-fill"></i>
                        Create Account
                    </h2>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            Registration successful! <a href="<?php echo BASE_URL; ?>/login.php" style="color: var(--success-color); font-weight: bold;">Log in here</a>
                        </div>
                    <?php endif; ?>

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

                    <?php if (!$success): ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person"></i> Username
                                </label>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                    required
                                    minlength="3"
                                    maxlength="50"
                                >
                            </div>

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
                                    minlength="8"
                                >
                                <small class="text-secondary" style="font-size: 0.875rem;">Minimum 8 characters</small>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Confirm Password
                                </label>
                                <input
                                    type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    class="form-control"
                                    required
                                    minlength="8"
                                >
                            </div>

                            <button type="submit" class="btn btn-primary w-full">
                                <i class="bi bi-person-plus"></i>
                                Register
                            </button>
                        </form>

                        <div class="mt-4 text-center">
                            <p class="text-secondary">
                                Already have an account?
                                <a href="<?php echo BASE_URL; ?>/login.php" style="color: var(--primary-color); font-weight: bold;">
                                    Log in here
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
