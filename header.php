<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang_code = $_SESSION['lang'] ?? 'pt'; // padrão: português
require_once __DIR__ . "/lang/{$lang_code}.php";
?>

<header>
    <nav>
        <div class="nav-container">
            <div>Sk8Nation</div>
            <ul class="nav-links">
                <li><a href="/PAP/home.php"><?= $lang['home'] ?></a></li>
                <li>
                    <a href="#"><?= $lang['clothing'] ?></a>
                    <ul class="dropdown">
                        <li><a href="#"><?= $lang['tshirts'] ?></a></li>
                        <li><a href="#"><?= $lang['sweats'] ?></a></li>
                        <li><a href="#"><?= $lang['pants'] ?></a></li>
                        <li><a href="#"><?= $lang['shorts'] ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><?= $lang['shoes'] ?></a>
                    <ul class="dropdown">
                        <li><a href="#">NikeSB</a></li>
                        <li><a href="#">Converse</a></li>
                        <li><a href="#">Last Resort</a></li>
                        <li><a href="#">Vans</a></li>
                        <li><a href="#">Raffles</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><?= $lang['accessories'] ?></a>
                    <ul class="dropdown">
                        <li><a href="#"><?= $lang['beanie'] ?></a></li>
                        <li><a href="#"><?= $lang['belts'] ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><?= $lang['skateboards'] ?></a>
                    <ul class="dropdown">
                        <li><a href="/PAP/decks.php"><?= $lang['decks'] ?></a></li>
                        <li><a href="/PAP/trucks.php"><?= $lang['trucks'] ?></a></li>
                        <li><a href="#"><?= $lang['wheels'] ?></a></li>
                        <li><a href="#"><?= $lang['bearings'] ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="brands.php"><?= $lang['brands'] ?></a>
                    <ul class="dropdown">
                        <li><a href="#">Butter</a></li>
                        <li><a href="#">Carhartt</a></li>
                        <li><a href="#">Converse</a></li>
                        <li><a href="#">NikeSB</a></li>
                        <li><a href="#">Polar Skate CO.</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="nav-icons">
            <?php if(isset($_SESSION["username"])):?>   
                <a href="/PAP/login/userprofi.php" class="icon" title="<?= $lang['profile'] ?>"><i class="fas fa-user"></i></a>
            <?php else:?>
                <a href="/PAP/login/login.php" class="icon" title="<?= $lang['login'] ?>"><i class="fas fa-user"></i></a>
            <?php endif;?>
            <a href="/PAP/cart.php" class="icon" title="<?= $lang['cart'] ?>"><i class="fas fa-shopping-cart"></i></a>
        </div> 

        <div class="language-selector">
            <a href="?lang=pt">PT</a> | <a href="?lang=en">EN</a>
        </div>
    </nav>
</header>
