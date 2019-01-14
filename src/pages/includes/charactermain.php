<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/User.class.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/Character.class.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/Constants.class.php";


/** @var User $user */
$user = $_SESSION["user"];
$char = $user->getLastUsed();

if (isset($_GET["p"])) {
    $what = $_GET["p"];
} else {
    $what = "*";
}
?>

<form class="api" data-what="<?php echo $what; ?>">
    <?php
    if ($what !== "*") {
        include $_SERVER["DOCUMENT_ROOT"]."/pages/includes/character/".Constants::getIndexOfSubpage($what).$what.".php";
    } else {
        foreach (glob($_SERVER["DOCUMENT_ROOT"]."/pages/includes/character/*.php") as $filename)
        {
            include $filename;
        }
    }
    ?>
    <div class="container btn-group fullwidth" role="group">
        <button class="btn btn-lg btn-primary mr-1 rounded-right operation" id="update">Update</button>
        <button class="btn btn-lg btn-primary ml-1 rounded-left" id="cancel">Cancel</button>
    </div>
</form>
