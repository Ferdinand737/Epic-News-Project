<?php
	session_start();
	$postID =  filter_input(INPUT_GET, "post_id",FILTER_SANITIZE_NUMBER_INT);  
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'headComponent.php'?>
		<title>Admin Portal</title>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	</head>
	<body>
		<header>
			<?php include 'navbarComponent.php'?>
		</header>
		<div id="divMain" class="container-fluid">
			<div class="row w-100" style="flex-wrap:nowrap;">
			<div class="content w-100">
				<div class="row w-100" style="margin-left:1em;" >
					<div class="card bg-secondary text-light w-100">
					<div class="card-header mx-auto"><h3>Manage posts<h3></div>
					<div class="card-body">
					<div class="row" style="flex-wrap:nowrap;">
						<div id="secManagePostPreview" class="pinLeft card car-body bg-info">
							<span><h6>Post ID:<h6></span>
							<span class="prevText" id="prevID">&nbsp;</span>
							<span><h6>User Name: <h6></span>
							<span class="prevText" id="prevUserID">&nbsp;</span>
							<span><h6>Post Title: <h6></span>
							<span class="prevText" id="prevTitle">&nbsp;</span>
							<span><h6>Post Visible Status: <h6></span>
							<span class="prevText" id="prevVisible">&nbsp;</span>
							<!--<span><h6>Post Votes: <h6></span>
							<span class="prevText" id="prevVotes">&nbsp;</span>-->
							<BR>
							<span class="prevText" id="BANPost"></span>
						</div>   
						<section id="secManagePosts" class="fillRight">
							<form action="titleSearchResultsPage.php" method="post">
								<input type="text" id="SearchTitle" name="SearchTitle" required="true" placeholder="Post title"/>
								<input id="TitleSubmit" type="submit" value="Search"/>
							</form>
							<?php $allPost = Post::AdminGetAll(); ?>
							<div class="scrollTable">
							<table id="PostTable" class="table-dark w-100" />
							<tr><th>POST ID</th><th>USER</th><th>TITLE</th><th>VISIBLE</th><th>VOTES</th><th>POST PAGE</th></tr>
							<?php
								foreach($allPost as $post){
								 echo "<tr onclick='getPostPreview(this)'><td>",$post->post_id,"</td><td>", $post->user->username,"</td><td>",$post->title,"</td><td>",$post->visible , "</td><td>",$post->votes , "</td><td>","<a href=","'postContentPage.php?post_id=",$post->post_id,"'>POST</a>", "</td></tr>";
								}
								?>
							</table>
							</div>
						 </section>
					</div>
					</div>
					</div>
				</div>
				
				<div class="row w-100" style="margin-left:1em;">
					<div class="card bg-secondary text-light w-100">
					<div class="card-header mx-auto"><h3>Manage users<h3></div>
						<div class="card-body">
						<div class="row" style="flex-wrap:nowrap;">
							<div id="secManageUserPreview" class="pinLeft card car-body bg-info">
								<span><h6>User ID: <h6></span>
								<span class="prevText" id="UserPreview_ID">&nbsp;</span>
								<span><h6>User Name: <h6></span>
								<span class="prevText" id="UserPreviewTitle">&nbsp;</span>
								<span><h6>User email: <h6></span>
								<span class="prevText" id="UserPreviewVisible">&nbsp;</span><br>
								<span class="prevText" id="BANUser"></span>
							</div>
							<section id="secManageUser" class="fillRight">
								<form action="userSearchResultsPage.php" method="post">
									<input type="text" id="SearchName" required="true" name="SearchName" placeholder="Username">
									<input id="UserSubmit" type="submit" value="Search" >
								</form>
								<?php $allUsers = User::AdminGetAll(); ?>
								<div class="scrollTable">
									<table id="UserTable" class="table-dark w-100"/>
									<tr><th class="w-auto text-nowrap">USER ID&nbsp;</th><th class="w-auto text-nowrap">USERNAME&nbsp;</th><th class="w-auto text-nowrap">EMAIL&nbsp;</th><th class="w-auto text-nowrap">ADMIN&nbsp;</th><th class="w-auto text-nowrap">ACTIVE&nbsp;</th><th>USER PAGE</th></tr>
									<?php
									foreach($allUsers as $user){
										echo "<tr onclick='getUserPreview(this)'><td>".$user->user_id."</td><td>".$user->username."</td><td>".$user->email."</td><td>".$user->is_admin."</td><td>".$user->is_active."</td><td>"."<a href="."'profilePage.php?user=".$user->user_id."'>PROFILE</a>"."</td</tr>";
									}
									?>
								</table>
								</div>
							</section>
						</div>
						</div>
					</div>
				</div>
				<div class="row w-100" style="margin-left:1em;">
					<div class="card bg-secondary text-light w-100">
					<div class="card-header mx-auto"><h3>Manage Topic<h3></div>
						<div class="card-body">
						<div class="row" style="flex-wrap:nowrap;">
							<div id="secManageTopicPreview" class="pinLeft card car-body bg-info">
								<span><h6>Topic ID: <h6></span>
								<span class="prevText" id="TopicPreview_ID">&nbsp;</span>
								<span><h6>Topic Title: <h6></span>
								<span class="prevText" id="TopicPreviewTitle">&nbsp;</span><br>
								<span><h6>Topic Creator: <h6></span>
								<span class="prevText" id="TopicPreviewCreator">&nbsp;</span>
								<span><h6>Topic Added: <h6></span>
								<span class="prevText" id="TopicPreviewAdded">&nbsp;</span><br>
								<span class="prevText" id="DeleteTopic"></span>
							</div>
							<section id="secManageTopic" class="fillRight">
								<form action="AdminPage.php" method="post">
									<input type="text" id="addTopic" required="true" name="AddTopic" placeholder="Sub Topic">
									<input type="submit" name="newTopic" class="btn btn-primary" value="Add Topic" >
								</form>
								<?php $allTopics = SubTopic::AdminGetAll(); ?>
								<div class="scrollTable">
									<table id="TopicTable" class="table-dark w-100"/>
									<tr><th class="w-auto text-nowrap">SUBTOPIC ID&nbsp;</th><th class="w-auto text-nowrap">TITLE&nbsp;</th><th class="w-auto text-nowrap">CREATED BY&nbsp;</th><th class="w-auto text-nowrap">CREATED DATE&nbsp;</th></tr>
									<?php
									foreach($allTopics as $topic){
										echo "<tr onclick='getTopicPreview(this)'><td>",$topic->sub_id,"</td><td>", $topic->title,"</td><td>",$topic->created_by->username,"</td><td>",$topic->creation_date,"</td</tr>";
									}
									?>
								</table>
								</div>
							</section>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			function getPostPreview(x) {
			  document.getElementById('prevID').innerHTML = document.getElementById("PostTable").rows[x.rowIndex].cells[0].innerHTML;
			  document.getElementById('prevUserID').innerHTML = document.getElementById("PostTable").rows[x.rowIndex].cells[1].innerHTML;
			  document.getElementById('prevTitle').innerHTML = document.getElementById("PostTable").rows[x.rowIndex].cells[2].innerHTML;
			  document.getElementById('prevVisible').innerHTML = document.getElementById("PostTable").rows[x.rowIndex].cells[3].innerHTML;
			  $string ="";
			  $banThisGuy =  document.getElementById('prevID').innerHTML;
			  document.getElementById('BANPost').innerHTML = $string.concat("<a href=banPage.php?post_id=",$banThisGuy,">BAN/DELETE POST</a>");
			}
			function getUserPreview(x) {
			  document.getElementById('UserPreview_ID').innerHTML = document.getElementById("UserTable").rows[x.rowIndex].cells[0].innerHTML;
			  document.getElementById('UserPreviewTitle').innerHTML = document.getElementById("UserTable").rows[x.rowIndex].cells[1].innerHTML;
			  document.getElementById('UserPreviewVisible').innerHTML = document.getElementById("UserTable").rows[x.rowIndex].cells[2].innerHTML;
			  $string ="";
			  $banThisGuy =  document.getElementById('UserPreview_ID').innerHTML;
			  document.getElementById('BANUser').innerHTML = $string.concat("<a href=banPage.php?user_id=",$banThisGuy,">BAN/DELETE USER</a>");
			}
			function getTopicPreview(x) {
			  document.getElementById('TopicPreview_ID').innerHTML = document.getElementById("TopicTable").rows[x.rowIndex].cells[0].innerHTML;
			  document.getElementById('TopicPreviewTitle').innerHTML = document.getElementById("TopicTable").rows[x.rowIndex].cells[1].innerHTML;
			  document.getElementById('TopicPreviewCreator').innerHTML = document.getElementById("TopicTable").rows[x.rowIndex].cells[2].innerHTML;
			  document.getElementById('TopicPreviewAdded').innerHTML = document.getElementById("TopicTable").rows[x.rowIndex].cells[3].innerHTML;
			  $string ="";
			  $banThisGuy =  document.getElementById('TopicPreview_ID').innerHTML;
			  document.getElementById('DeleteTopic').innerHTML = $string.concat("<a href=banPage.php?sub_id=",$banThisGuy,">DELETE TOPIC</a>");
			 
			}
		</script>
		<?php
        if( isset($_POST['newTopic']) ){
            $userID =  $_SESSION['current_user'];
            $sub_topic = filter_input(INPUT_POST,"AddTopic",FILTER_SANITIZE_STRING);
			if(isset($userID)){
				SubTopic::AdminNewSubTopic($sub_topic, $userID);
            }
            echo "<script>
            if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
            }
            $( document ).ready(function() {
                $('body').load(window.location.href = 'AdminPage.php');
                });
            </script>";
        }
    ?>
	<div class="d-flex flex-row align-items-center">
		<canvas id="post-chart" width="400" height="400"></canvas>
		<canvas id="user-chart" width="400" height="400"></canvas>
		<canvas id="comment-chart" width="400" height="400"></canvas>
	</div>
	</body>
</html>