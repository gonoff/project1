<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Project1</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/reset.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/variables.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/layout.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/components.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/utilities.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <div class="container">
                <a href="<?php echo BASE_URL; ?>/" class="brand">
                    <i class="bi bi-code-square"></i>
                    <span>Project1</span>
                </a>
                <ul class="nav-menu">
                    <?php if (isLoggedIn()): ?>
                        <li><a href="<?php echo BASE_URL; ?>/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                        <li>
                            <span style="color: var(--text-secondary);">
                                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </span>
                        </li>
                        <li><a href="<?php echo BASE_URL; ?>/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/register.php"><i class="bi bi-person-plus"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
