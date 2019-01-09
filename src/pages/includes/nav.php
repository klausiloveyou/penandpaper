<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

/** @var User $user */
$user = $_SESSION["user"];
$lastUsed = $user->getLastUsed();
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="/pages/characters.php"><?php echo (is_null($lastUsed)) ? "No Character" : $lastUsed->getName(); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">

        <!-- Menu items for active character -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link <?php echo (is_null($lastUsed)) ? "disabled" : ""; ?>" href="/pages/characters.php?p=profile">Profile </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (is_null($lastUsed)) ? "disabled" : ""; ?>" href="/pages/characters.php?p=armory">Armory </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (is_null($lastUsed)) ? "disabled" : ""; ?>" href="/pages/characters.php?p=skills">Skills </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (is_null($lastUsed)) ? "disabled" : ""; ?>" href="/pages/characters.php?p=talents">Talents </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (is_null($lastUsed)) ? "disabled" : ""; ?>" href="/pages/characters.php?p=belongings">Belongings </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (is_null($lastUsed)) ? "disabled" : ""; ?>" href="/pages/characters.php?p=spells">Spells </a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo strtoupper($user->getName()); ?></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                    <?php include $_SERVER["DOCUMENT_ROOT"]."/pages/includes/admindropdownitems.php"; ?>
                    <a class="dropdown-item" href="/pages/newcharacter.php">New Character...</a>
                    <div class="dropdown-divider"></div>
                    <?php include $_SERVER["DOCUMENT_ROOT"]."/pages/includes/chardropdownitems.php"; ?>
                    <a class="dropdown-item" href="/pages/changepw.php">Change Password</a>
                    <a class="dropdown-item" href="/pages/util/logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>