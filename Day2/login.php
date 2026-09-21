<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if (currentUser($database) !== null) {
    redirectTo('profile.php');
}

$error = null;
$email = '';
$returnTo = (string) ($_GET['return_to'] ?? $_POST['return_to'] ?? 'profile.php');
if (!preg_match('/^[a-zA-Z0-9_-]+\.php(?:\?.*)?$/', $returnTo)) {
    $returnTo = 'profile.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $user = authenticateUser($database, $email, $password);

    if ($user === null) {
        $error = 'Email or password is incorrect.';
    } else {
        loginUser($user);
        redirectTo($returnTo);
    }
}
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login | ShivKrupa Art Jewellery</title></head>
<body>
<main>
    <h1>Log in</h1>
    <?php if ($error): ?><p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form method="post" action="login.php"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="return_to" value="<?= htmlspecialchars($returnTo, ENT_QUOTES, 'UTF-8') ?>">
        <label>Email <input type="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button type="submit">Log in</button>
    </form>
    <p>Need an account? <a href="register.php">Register</a>.</p>
</main>
</body>
</html>
