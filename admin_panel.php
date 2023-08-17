<?php

include 'inc/database.php';
include 'inc/header.php';?>
<main>
<?php if (isset($_SESSION['admin'])): ?>


<?php else: ?>
    <h2>You must be logged it as admin to see this page!</h2>
<?php endif; ?>

    </main>
<?php include 'inc/footer.php';