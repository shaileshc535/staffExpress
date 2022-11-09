<div class="con_right">


    <span class="boldTxt">FOLLOW ME ON</span>


    <!--<img src="images/icon_rss.jpg" class="icon">-->


    <a href="http://twitter.com/billyschwer" target="_blank"><img src="images/icon_twitter.jpg" class="icon"></a>


    <!--<a href="http://www.facebook.com/billyschwer" target="_blank"><img src="images/icon_fb.jpg" class="icon"></a>-->


    <!--<img src="images/icon_unknown.jpg" class="icon">


                <img src="images/icon_youtube.jpg" class="icon">-->





    <div class="network_sec">


        <div id="follow_sec">


            <img src="images/follow_twitter.png" style="margin-bottom:6px;">


            <span class="billy_img"><img src="images/billy_small.jpg"></span>


            <span id="twitter_update_list" class="follow_twitter_txt">





                <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js">


                </script>


                <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/Chandrani2010.json?callback=twitterCallback2&count=1">


                </script>


                <a href="http://twitter.com/billyschwer" target="_blank">MORE</a>


            </span>


        </div>


        <!--<a href="http://twitter.com/billyschwer" target="_blank"><img src="images/follow_img.jpg"></a>-->





        <div class="likebox">
            <div id="fb-root"></div>
            <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
            <fb:like-box href="http://www.facebook.com/billyschwer" width="235" show_faces="true" stream="false" header="false" css="css/style.css?3"></fb:like-box>


        </div>





        <?php


        $blogContent = dbQuery("SELECT * FROM blog order by postdate desc");


        ?>


        <div class="likebox" style="border:1px solid #AAAAAA; width:222px; padding:5px;">


            <span class="boldTxt" style="padding-top:2px;">LATEST BLOG POSTS:</span>


            <br /><br />


            <?php


            while ($blog = dbFetchObject($blogContent)) {


            ?>


                <a href="blog.php?blog_id=<?php echo stripslashes($blog->blog_id); ?>"><?php echo stripslashes($blog->title); ?> &raquo;</a><br />





                <?php //echo stripslashes(stripslashes($blog->description));
                ?>





            <?php


            }


            ?>


        </div>





    </div>





</div>