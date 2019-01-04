<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/source/db/util.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/ui/nav.php";

$ui = $_SESSION["user_id"];
$ia = isAdmin($ui);
$un = getUserNameByID($ui);
$cid = $_SESSION["char_id"];

?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="/pages/characters.php">Torendil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">

        <!-- Menu items for active character -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <!--
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
            -->
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo strtoupper($un); ?></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                    <?php if ($ia) {echo getAdminDropDownItems();} ?>
                    <a class="dropdown-item" href="#">New Character...</a>
                    <div class="dropdown-divider"></div>

                    <!-- Loop through Characters (if more than 1) -->
<!--                    <h6 class="dropdown-header">Change Character:</h6>-->
<!--                    <a class="dropdown-item" href="#">Torendil</a>-->
<!--                    <div class="dropdown-divider"></div>-->

                    <a class="dropdown-item" href="/pages/changepw.php">Change Password</a>
                    <a class="dropdown-item" href="/pages/util/logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>