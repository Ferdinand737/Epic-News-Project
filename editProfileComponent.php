<!-- Props:
        $targetUser 
-->

<div class="col-md-8 p-5 secUserProfile"style="margin-top:66px;">
    <?php 
        if (isset($_SESSION["image_error_message"])) {
            echo "<p style='color: red; width: 250px'>" . $_SESSION["image_error_message"] . "</p>";
            unset($_SESSION["image_error_message"]);
        }
    ?>
    <div id="update-profile-pic">
        <p>Update Profile Picture</p>
        <form enctype="multipart/form-data" action="imageHandler.php" method="post">
            <div>
                <input type="file" name="profile-pic" required onchange="this.form.submit()" accept="image/*">
            </div>
        </form>
    </div>

    <br>
    <br>

    <div id="update-email">     
        <form action="updateEmail.php" method="POST">
            <?php
                if (isset($_SESSION["email_error_message"])) {
                    echo "<p style='color: red; width: 250px'>" . $_SESSION["email_error_message"] . "</p>";
                    unset($_SESSION["email_error_message"]);
                }
                if (isset($_SESSION["email_success_message"])) {
                    echo "<p style='color: green; width: 250px'>" . $_SESSION["email_success_message"] . "</p>";
                    unset($_SESSION["email_success_message"]);
                }
            ?>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="hidden" name="user_id" value="<?php echo $targetUser->user_id;?>">
                <input style="width: 40%;" type="email" class="form-control" name="email" id="email" placeholder="<?php echo $targetUser->email?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div> 

    <br>
    <br>

    <div id="update-about-me">
        <form action="updateAboutMe.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $targetUser->user_id;?>">
            <textarea class="form-control" name="about-me" rows="4" required placeholder="Write something about yourself"></textarea>
            <button type="submit" class="btn btn-primary">Update</button> 
        </form>
    </div>
</div>
