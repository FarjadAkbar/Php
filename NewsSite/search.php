<?php include 'header.php'; ?>
    <div id="main-content">
      <div class="container">
        <div class="row">
            <div class="col-md-8">                
               <!-- post-container -->
               <div class="post-container">
                    <?php                    
                    include "config.php";
                    if(isset($_GET['search'])){
                        $search_term=mysqli_real_escape_string($conn,$_GET['search']);
                    }

                    $limit=5;
                    if(isset($_GET['page'])){                        
                        $page=$_GET['page'];
                    }
                    else{
                        $page = 1;
                    }
                    $offset=(($page - 1) * $limit);
                    
                    $query = "SELECT post.post_id, post.title, post.author, post.description, post.category, category.category_name, post.post_date, post.post_img,user.username FROM post 
                              LEFT JOIN category ON post.category=category.category_id 
                              LEFT JOIN user ON post.author=user.user_id
                              WHERE post.title like '%{$search_term}%' OR
                                    post.description like '%{$search_term}%' OR
                                    user.username like '%{$search_term}%' OR
                                    category.category_name like '%{$search_term}%'
                              ORDER BY post.post_id DESC LIMIT {$offset},{$limit}";
                    
                    $result = mysqli_query($conn,$query) or die("Query Failed");
                    if(mysqli_num_rows($result) > 0){  
                        while($row=mysqli_fetch_assoc($result)){                                                           
                    ?>
                    
                    <h2 class="page-heading text-capitalize">search : <?php echo $search_term; ?></h2>
                    <div class="post-content">
                        <div class="row">
                            <div class="col-md-4">
                                <a class="post-img" href="single.php?id=<?php echo $row['post_id']; ?>"><img src="admin/upload/<?php echo $row['post_img']; ?>" alt=""/></a>
                            </div>
                            <div class="col-md-8">
                                <div class="inner-content clearfix">
                                    <h3><a href='single.php?id=<?php echo $row['post_id']; ?>'><?php echo $row['title']; ?></a></h3>
                                    <div class="post-information">
                                        <span>
                                            <i class="fa fa-tags" aria-hidden="true"></i>
                                            <a href='category.php?cid=<?php echo $row['category']; ?>'><?php echo $row['category_name']; ?></a>
                                        </span>
                                        <span>
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                            <a href='author.php?aid=<?php echo $row['author']; ?>'><?php echo $row['username']; ?></a>
                                        </span>
                                        <span>
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            <?php echo $row['post_date']; ?>
                                        </span>
                                    </div>
                                    <p class="description">
                                        <?php echo substr($row['description'],0,200)."...." ?>
                                    </p>
                                    <a class='btn btn-primary text-capitalize pull-right' href='single.php?id=<?php echo $row['post_id']; ?>'>read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                        }
                        else{
                            echo "<h2 class='alert alert-danger'>No record found</h2>";
                        }
                        $query1="SELECT * FROM `post` JOIN `user` ON post.author=user.user_id
                                 JOIN `category` ON post.category=category.category_id
                                 WHERE post.title like '%{$search_term}%'OR
                                       post.description like '%{$search_term}%' OR
                                       user.username like '%{$search_term}%' OR
                                       category.category_name like '%{$search_term}%'";

                        $result1=mysqli_query($conn,$query1);
                        $row1=mysqli_fetch_assoc($result1);

                        if(mysqli_num_rows($result1)>0){

                        $total_record=mysqli_num_rows($result1);
                        $total_page=ceil($total_record/$limit);
                            
                        echo "<ul class='pagination'>";                             
                        if($page > 1){
                            echo "<li><a href='search.php?search={$search_term}&page=".($page-1)."'>Prev</a></li>";
                        }
                        if($total_page>1){
                            for($i=1;$i<=$total_page;$i++){
                                if($i==$page){
                                    $active='active';
                                }
                                else{
                                    $active='';
                                }
                                echo "<li class=".$active."><a href='search.php?search={$search_term}&page=$i'>$i</a></li>";
                            }
                        }
                       if($total_page > $page){
                            echo "<li><a href='search.php?search={$search_term}&page=".($page + 1)."'>Next</a></li>";
                       }  
                        echo "</ul>";    
                        }

                        
                    ?>                         
                </div><!-- /post-container -->
            </div>
            <?php include 'sidebar.php'; ?>
        </div>
      </div>
    </div>
<?php include 'footer.php'; ?>
