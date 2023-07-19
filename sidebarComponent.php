<div id="divLeftColumn" class="container" style="max-width:200px; z-index:999;">
    <br>
    <br>
    <nav>
        <ul>
            <li> <a class="side-link" href="#Top">Top</a> </li>
            <li> <a class="side-link" href="#Bottom">Bottom</a> </li>
        </ul>
    </nav>
    <div>
    <div class="panel panel-default">
        <br>
        <br>
        <h4>Topics</h4>
        <ul>
        <?php
            $allSubTopics = SubTopic::getAll();
            foreach($allSubTopics as $SubTopic){
                echo "<li> <a class='side-link' href='TopicPage.php?topicID=",$SubTopic->sub_id,"'>",$SubTopic->title,"</a> </li>";
            }
            ?>
        <ul>
        </div>
    </div>

</div> 