
<header>
        <nav>
             <div class="nav-container">
                    <div >SKATESHOP</div>
                    <ul class="nav-links">
                        <li><a href="/PAP/h >Home</a></li>
                        <li>
                            <a href="#">Apparel</a>
                            <ul class="dropdown">
                                <li><a href="#"> Tees</a></li>
                                <li><a href="#"> Sweats</a></li>
                                <li><a href="#"> Pants</a></li>
                                <li><a href="#">Shorts</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Footwear</a>
                            <ul class="dropdown">
                                <li><a href="#">NikeSB</a></li>
                                <li><a href="#">Converse</a></li>
                                <li><a href="#">Last Resort</a></li>
                                <li><a href="#">Vans</a></li>
                                <li><a href="#">Raffles</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Accessories</a>
                            <ul class="dropdown">
                                <li><a href="#">Beanies</a></li>
                                <li><a href="#">Belts</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Skateboards</a>
                            <ul class="dropdown">
                                <li><a href="decks.php">Decks</a></li>
                                <li><a href="trucks.php">Trucks</a></li>
                                <li><a href="#">Wheels</a></li>
                                <li><a href="#">Bearings</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="brands.php">Brands</a>
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
                        <a href="/PAP/login/userprofi.php" class="icon"><i class="fas fa-user"></i></a>
                    <?php else:?>
                        <a href="/PAP/login/login.php" class="icon"><i class="fas fa-user"></i></a>
                    <?php endif;?>
                    <a href="cart.php" class="icon"><i class="fas fa-shopping-cart"></i></a>
                </div>    
        </nav>
    </header>