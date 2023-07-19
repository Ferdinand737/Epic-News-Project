<nav class="navbar navbar-expand-xl">
	<a class="navbar-brand" style="padding-right:10em;" href="homePage.php">Epic News</a>
	<button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation">
	    <span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="main-navigation">
		<ul class="navbar-nav d-flex align-items-center">
			<li style="margin-top: 10px; margin-bottom:0px;">
				<form action="titleSearchResultsPage.php" method="post">
					<div class="d-flex align-items-center">
						<input style="width:100%" type="text" id="SearchTitle" name="SearchTitle" required="true" placeholder="Post title" />
						<input id="search" type="submit" value="Search Post" />
					</div>
				</form>	
			</li>
			<li  style="margin-top: 10px; margin-bottom:0px;">
				<form action="userSearchResultsPage.php" method="post">
					<div class="d-flex align-items-center">
						<input style="width:100%" type="text" id="SearchName" name="SearchName" required="true" placeholder="Username" />
						<input id="search" type="submit" value="Search User" />
					</div>
				</form>	
			</li>
			<?php if($_SESSION['isAdmin'] == 1){?>
				<li class="nav-item">
					<a class="nav-link" style=" text-align: end;" href="loadDatabase.php">Reset Database</a>
				</li>
			<?php } ?>
			<li class="nav-item" style=" text-align: end;">
				<?php
					if(isset($_SESSION['current_user'])){
						$href = "createPage.php";
					}else{
						$href = "loginPage.php";
					}
				?>
			    <a class="nav-link" style=" text-align: end;" href=<?php echo $href; ?>>Create Post</a>
			</li>
			<?php
			if(isset($_SESSION['current_user']))
			{
				if($_SESSION['current_user']){
					echo "<li class='nav-item'>";
					echo "<a class='nav-link' style=' text-align: end;' href='profilePage.php?user={$_SESSION['current_user']}'>{$_SESSION['username']}</a>";
					echo "</li>";
					echo "<li class='nav-item'>";
					echo "<a class='nav-link' href='logout.php'>Logout</a>";
					echo "</li>";
				}
				if($_SESSION['isAdmin'] == 1){
					echo "<li class='nav-item'>";
					echo "<a class='nav-link' style=' text-align: end;' href='AdminPage.php'>Admin Portal</a>";
					echo "</li>";
				}
			}
			else{
					echo "<li class='nav-item'>";
					echo "<a class='nav-link' style=' text-align: end;' href='loginPage.php'>Login</a>";
					echo "</li>";
				}
			?>
		</ul>
    </div>
</nav>