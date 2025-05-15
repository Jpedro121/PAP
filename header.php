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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  .nav-links {
    list-style: none;
    display: flex;
    gap: 1.2rem;
    align-items: center;
  }

  .nav-links li {
    position: relative;
  }

  .nav-links a {
    color: #000;
    text-decoration: none;
    font-weight: 500;
  }

  .dropdown {
    display: none;
    position: absolute;
    background: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    z-index: 99;
    min-width: 150px;
    border-radius: 8px;
    animation: fadeIn 0.3s ease;
  }

  .nav-links li:hover .dropdown {
    display: block;
  }

  .dropdown li a {
    display: block;
    padding: 0.5rem 1rem;
    white-space: nowrap;
  }

  .dropdown li a:hover {
    background-color: #f5f5f5;
  }

  .icon-caret {
    margin-left: 6px;
    transition: transform 0.3s ease;
  }

  .nav-links li:hover .icon-caret {
    transform: rotate(180deg);
  }

  .nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
   }

  .language-selector {
    text-align: right;
    padding: 0 2rem;
  }

  .nav-icons {
    padding: 0 2rem;
  }

  .icon {
    margin-left: 1rem;
    color: #333;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

<header>
  <nav>
    <div class="nav-container">
      <div><strong>Sk8Nation</strong></div>
      <ul class="nav-links">
        <li><a href="/PAP/home.php"><?= $lang['home'] ?></a></li>

        <li>
          <a href="#">
            <?= $lang['clothing'] ?>
            <i class="fas fa-angle-down icon-caret"></i>
          </a>
          <ul class="dropdown">
            <li><a href="/PAP/tshirts.php"><?= $lang['tshirts'] ?></a></li>
            <li><a href="/PAP/sweats.php"><?= $lang['sweats'] ?></a></li>
            <li><a href="/PAP/calças.php"><?= $lang['pants'] ?></a></li>
            <li><a href="/PAP/calcoes.php"><?= $lang['shorts'] ?></a></li>
          </ul>
        </li>

        <li>
          <a href="#">
            <?= $lang['shoes'] ?>
            <i class="fas fa-angle-down icon-caret"></i>
          </a>
          <ul class="dropdown">
            <li><a href="/PAP/NikeSB.php">NikeSB</a></li>
            <li><a href="/PAP/converse.php">Converse</a></li>
            <li><a href="/PAP/lastresort.php">Last Resort</a></li>
            <li><a href="/PAP/Vans.php">Vans</a></li>
          </ul>
        </li>

        <li>
          <a href="gorros.php">
            <?= $lang['accessories'] ?>
            <i class="fas fa-angle-down icon-caret"></i>
          </a>
          <ul class="dropdown">
            <li><a href="/PAP/gorros.php"><?= $lang['beanie'] ?></a></li>
            <li><a href="/PAP/cintos.php"><?= $lang['belts'] ?></a></li>
          </ul>
        </li>

        <li>
          <a href="#">
            <?= $lang['skateboards'] ?>
            <i class="fas fa-angle-down icon-caret"></i>
          </a>
          <ul class="dropdown">
            <li><a href="/PAP/decks.php"><?= $lang['decks'] ?></a></li>
            <li><a href="/PAP/trucks.php"><?= $lang['trucks'] ?></a></li>
            <li><a href="/PAP/wheels.php"><?= $lang['wheels'] ?></a></li>
            <li><a href="/PAP/rolamentos.php"><?= $lang['bearings'] ?></a></li>
          </ul>
        </li>

        <li>
          <a href="brands.php">
            <?= $lang['brands'] ?>
            <i class="fas fa-angle-down icon-caret"></i>
          </a>
          <ul class="dropdown">
            <li><a href="#">Butter</a></li>
            <li><a href="#">Carhartt</a></li>
            <li><a href="#">Converse</a></li>
            <li><a href="#">NikeSB</a></li>
            <li><a href="#">Polar Skate CO.</a></li>
          </ul>
        </li>
      </ul>

      <div class="nav-icons">
        <?php if(isset($_SESSION["username"])):?>   
          <a href="/PAP/login/userprofi.php" class="icon" title="<?= $lang['profile'] ?>"><i class="fas fa-user"></i></a>
        <?php else:?>
          <a href="/PAP/login/login.php" class="icon" title="<?= $lang['login'] ?>"><i class="fas fa-user"></i></a>
        <?php endif;?>
        <a href="/PAP/cart.php" class="icon" title="<?= $lang['cart'] ?>"><i class="fas fa-shopping-cart"></i></a>
      </div>
    </div>

    <div class="language-selector">
      <a href="?lang=pt">PT</a> | <a href="?lang=en">EN</a>
    </div>
  </nav>
</header>
