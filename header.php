<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  .nav-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
  }

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
    background: #fff;
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
      <!-- Logo -->
      <div>
        <a href="/PAP/home.php">
          <img src="/PAP/static/images/marcas/Sk8Nationlogo.png" alt="Sk8Nation Logo" style="height: 80px;">
        </a>
      </div>

      <!-- Navegação principal -->
      <ul class="nav-links">
        <li><a href="/PAP/home.php">Home</a></li>

        <li>
          <a href="#">Clothing <i class="fas fa-angle-down icon-caret"></i></a>
          <ul class="dropdown">
            <li><a href="/PAP/tshirts.php">T-shirts</a></li>
            <li><a href="/PAP/sweats.php">Sweats</a></li>
            <li><a href="/PAP/calças.php">Pants</a></li>
            <li><a href="/PAP/calcoes.php">Shorts</a></li>
          </ul>
        </li>

        <li>
          <a href="#">Shoes <i class="fas fa-angle-down icon-caret"></i></a>
          <ul class="dropdown">
            <li><a href="/PAP/NikeSB.php">NikeSB</a></li>
            <li><a href="/PAP/converse.php">Converse</a></li>
            <li><a href="/PAP/lastresort.php">Last Resort</a></li>
            <li><a href="/PAP/Vans.php">Vans</a></li>
          </ul>
        </li>

        <li>
          <a href="#">Accessories <i class="fas fa-angle-down icon-caret"></i></a>
          <ul class="dropdown">
            <li><a href="/PAP/gorros.php">Beanie</a></li>
            <li><a href="/PAP/cintos.php">Belts</a></li>
          </ul>
        </li>

        <li>
          <a href="#">Skateboards <i class="fas fa-angle-down icon-caret"></i></a>
          <ul class="dropdown">
            <li><a href="/PAP/decks.php">Decks</a></li>
            <li><a href="/PAP/trucks.php">Trucks</a></li>
            <li><a href="/PAP/wheels.php">Wheels</a></li>
            <li><a href="/PAP/rolamentos.php">Bearings</a></li>
          </ul>
        </li>

        <li>
  <a href="/PAP/brands.php">Brands <i class="fas fa-angle-down icon-caret"></i></a>
  <ul class="dropdown">
    <li><a href="/PAP/produtos_por_marca.php?marca=Butter">Butter</a></li>
    <li><a href="/PAP/produtos_por_marca.php?marca=Carhartt">Carhartt</a></li>
    <li><a href="/PAP/produtos_por_marca.php?marca=Converse">Converse</a></li>
    <li><a href="/PAP/produtos_por_marca.php?marca=NikeSB">NikeSB</a></li>
    <li><a href="/PAP/produtos_por_marca.php?marca=Polar%20Skate%20CO.">Polar Skate CO.</a></li>
  </ul>
</li>


      <!-- Ícones -->
      <div class="nav-icons">
        <?php if (isset($_SESSION["username"])): ?>   
          <a href="/PAP/login/userprofi.php" class="icon" title="Perfil"><i class="fas fa-user"></i></a>
          <?php else: ?>
          <a href="/PAP/login/login.php" class="icon" title="Login"><i class="fas fa-user"></i></a>
        <?php endif; ?>
        <a href="/PAP/cart.php" class="icon" title="Cart"><i class="fas fa-shopping-cart"></i></a>
      </div>
    </div>
  </nav>
</header>
