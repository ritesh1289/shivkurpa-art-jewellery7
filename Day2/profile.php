<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
$user = requireAuthentication($database);
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Profile | ShivKrupa Art Jewellery</title></head>
<body>
<main>
    <h1>Your profile</h1>
    <dl>
        <dt>Name</dt><dd><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></dd>
        <dt>Email</dt><dd><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></dd>
    </dl>
    <form method="post" action="logout.php"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>"><button type="submit">Log out</button></form>
</main>
</body>
</html>
