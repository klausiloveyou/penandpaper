<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/User.class.php";

/** @var User $user */
$user = $_SESSION["user"];

$isAdmin = ($user->getRole() === "admin") ? true : false;
?>

<?php if ($isAdmin): ?>
    <a class="dropdown-item" href="/pages/admin/newuser.php">New User...</a>
<?php endif; ?>