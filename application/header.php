<?php 
echo '<nav class="navbar navbar-expand-sm mx-0">
<button type="button" id="sidebarCollapse" class="btn btn-info">
<i class="fas fa-align-left"></i>
<span>Toggle Side Menu</span>
</button>

<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav ml-auto">
<li class="nav-item dropdown">
	<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	' . $_SESSION['username'] . '
	</a>
	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
	<a class="dropdown-item" href="#">Action</a>
	<a class="dropdown-item" href="#">Help</a>
	<div class="dropdown-divider"></div>
	<a class="dropdown-item" href="../includes/logout.inc.php">Logout</a>
	</div>
</li>
</ul>
</div>
</nav>';
?>