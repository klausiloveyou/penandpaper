<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/User.class.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/Character.class.php";

/** @var User $user */
$user = $_SESSION["user"];

$chars = $user->getCharObjectIDs();
?>

<?php if (!is_null($chars) && count($chars) > 1): ?>
    <h6 class="dropdown-header">Change Character:</h6>
    <?php foreach ($chars as $c): ?>
        <?php
        try {
            $co = new Character($c);
            if ($co->getID()->__toString() === $user->getLastUsed()->getID()->__toString()) {
                continue;
            }
        } catch (Exception $e) {
            continue;
        }
        ?>
        <a class="dropdown-item" href="/pages/util/changecharacter.php?c=<?php echo $co->getID()->__toString(); ?>"><?php echo $co->getProfile()->name; ?></a>
    <?php endforeach; ?>
    <div class="dropdown-divider"></div>
<?php endif; ?>