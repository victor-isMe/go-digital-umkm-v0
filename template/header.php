<?php session_start(); ?>

<nav class="navbar">

<p class="logo">Go Digital UMKM</p>

<div class="hamburger" onclick="toggleMenu()">
    ☰
</div>

<ul id="navMenu">

<li>
<a href="index.html">
Home
</a>
</li>

<li>
<a href="produk.php">
Products
</a>
</li>
<li>
    <a href="form.php">Add Product</a>
</li>

<?php if (!isset($_SESSION["login"])): ?>
    <li>
        <a href="login.php">Login</a>
    </li>
<?php else: ?>
    <li>
        <a href="logout.php">Logout</a>
    </li>
<?php endif; ?>

<!-- <li>
<a href="keranjang.html">
🛒
</a>
</li> -->

</ul>

</nav>