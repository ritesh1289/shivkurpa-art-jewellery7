<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if (currentUser($database) !== null) {
    redirectTo('profile.php');
}

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

    if ($name === '' || strlen($name) > 120) {
        $errors[] = 'Enter a name between 1 and 120 characters.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
        $errors[] = 'Enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if (!hash_equals($password, $passwordConfirmation)) {
        $errors[] = 'Passwords do not match.';
    }
    if (!$errors && findUserByEmail($database, $email) !== null) {
        $errors[] = 'An account already exists for that email address.';
    }

    if (!$errors) {
        $userId = registerUser($database, $name, $email, $password);
        loginUser(['id' => $userId]);
        redirectTo('profile.php');
    }
}
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Register | ShivKrupa Art Jewellery</title></head>
<body>
<main>
    <h1>Create an account</h1>
    <?php if ($errors): ?><ul><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?></ul><?php endif; ?>
    <form method="post" action="register.php"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
        <label>Name <input name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" required maxlength="120"></label>
        <label>Email <input type="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required maxlength="190"></label>
        <label>Password <input type="password" name="password" required minlength="8"></label>
        <label>Confirm password <input type="password" name="password_confirmation" required minlength="8"></label>
        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="login.php">Log in</a>.</p>
</main>
</body>
</html>
