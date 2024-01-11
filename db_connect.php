<!--  File: db_connect.php                          -->
<!--  Authors: Destiny Delancey & Yanni Turnquest   -->
<!--  Date: March 29, 2023                          -->

<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <title>Movie Recommendation Database</title> 
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <link rel = "stylesheet" href= "design.css">
    </head> 

    <body>
        <div class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <div class="navbar-header">
                        <button class="navbar-toggler navbar-toggle" type="button" data-toggle="collapse" data-target="#mobile_menu">
                        <span class="navbar-toggler-icon"></span>
                        </button>

                        <a href="#" class="navbar-brand">MOVIES.COM</a>
                    </div>

                    <div class="navbar-collapse collapse" id="mobile_menu">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#">Home</a></li>
                            <li><a href="#" class="dropdown-toggle" data-toggle="dropdown">About Us <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">History</a></li>
                                    <li><a href="#">Employees</a></li>
                                    <li><a href="#">Info</a></li>
            
                                </ul>
                            </li>
                            <li><a href="#">Welcome</a></li>
                            <li><a href="#">Services</a></li>
                            <li><a href="#">Gallery</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                        <ul class="nav navbar-nav">
                            <li>
                                <form action="" class="navbar-form">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="search" name="search" id="" placeholder="Search Anything Here..." class="form-control">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                                        </div>
                                    </div>
                                </form>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
                            <li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-log-in"></span> Login / Sign Up <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Login</a></li>
                                    <li><a href="#">Sign Up</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="container">
        <h1>Welcome to our Movie Recommendation Database:</h1>
        <div class="card">
            <div class="card-header">Search by:</div>
            <div class="card-body">
                <form method="GET">
                <div class="form-group">
                    <label for="movieName">Movie Name:</label>
                    <input type="text" class="form-control" id="movieName" name="movieName">
                </div>
                <div class="form-group">
                    <label for="genre">Genre:</label>
                    <input type="text" class="form-control" id="genre" name="genre">
                </div>
                <div class="form-group">
                    <label for="year">Year of Release:</label>
                    <input type="text" class="form-control" id="year" name="year">
                </div>
                <div class="form-group">
                    <label for="castMember">Cast Member:</label>
                    <input type="text" class="form-control" id="castMember" name="castMember">
                </div>
                <div class="form-group">
                    <label for="tag">Tags:</label>
                    <input type="text" class="form-control" id="tag" name="tag">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                </form>
            </div>

            <?php
                $host = "localhost";
                $user = "postgres";
                $password = "yanncuando";
                $dbname = "MovieDatabase"; 

                // Create connection
                $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);

                // Check connection
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //SQL Query
                $sql = "SELECT DISTINCT movie.movie_id, title, release_date
                FROM movies.movie 
                JOIN movies.movie_cast ON movies.movie.movie_id = movies.movie_cast.movie_id
                JOIN movies.person ON movies.movie_cast.person_id = movies.person.person_id
                JOIN movies.movie_genres ON movies.movie.movie_id = movies.movie_genres.movie_id 
                JOIN movies.genre ON movies.movie_genres.genre_id = movies.genre.genre_id
                JOIN movies.movie_keywords ON movies.movie.movie_id = movies.movie_keywords.movie_id 
                JOIN movies.keyword ON movies.movie_keywords.keyword_id = movies.keyword.keyword_id
                WHERE 1=1"; 
                
                $conditions = [];

                if (isset($_GET["movieName"]) && !empty($_GET["movieName"])) {
                    $conditions[] = "title ILIKE '%" . $_GET["movieName"] . "%'";
                }
                if (isset($_GET["year"]) && !empty($_GET["year"])) {
                    $conditions[] = "EXTRACT(YEAR FROM release_date) = " . $_GET["year"];
                }
                if (isset($_GET["castMember"]) && !empty($_GET["castMember"])) {
                    $conditions[] = "movies.person.person_name ILIKE '%" . $_GET["castMember"] . "%'";
                }
                if (isset($_GET["genre"]) && !empty($_GET["genre"])) {
                    $conditions[] = "movies.genre.genre_name ILIKE '%" . $_GET["genre"] . "%'";
                }
                if (isset($_GET["tag"]) && !empty($_GET["tag"])) {
                    $conditions[] = "movies.keyword.keyword_name ILIKE '%" . $_GET["tag"] . "%'";
                }


            if (count($conditions) > 0) {
            $sql .= " AND " . implode(" AND ", $conditions);

            }

            //execute the query
            $result = $conn->query($sql);

            //check for 0 rows (no results)
            if ($result->rowCount() > 0) {
                // output data of each row

                echo "<table class=\"table table-bordered table-striped\">";
                echo "<tr><th>Movie Name</th><th>Year of Release</th></tr>"; // Updated table headers

                //iterate over the query results
                //iterate over the query results
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<tr>";
                    echo "<td>";
                    echo "<span class='movie-name' data-movie-id='" . $row["movie_id"] . "'>" . $row["title"] . "</span>";
                    echo "<i class='fas fa-info-circle' title='Hover for overview'></i>";
                    echo "</td>";
                    echo "<td>" . date('Y', strtotime($row["release_date"])) . "</td>";
                    echo "</tr>";
                }


                echo "</table>";

            } else 
            {
                echo "0 results";
            }

            $conn = null;
            ?>

            <script>
                $(document).ready(function() 
                {
              // Function to get movie overview
                  function getMovieOverview(movieId, callback) 
                  {
                    $.ajax({
                      url: 'get-overview.php',
                      type: 'POST',
                      data: { movie_id: movieId },
                      success: function(response) 
                      {
                        callback(response);
                      },
                      error: function() 
                      {
                        callback(null);
                      }
                });
              }

                // Create a tooltip element
                var tooltip = $('<div class="movie-tooltip"></div>').appendTo('body');
                // Variables for handling the tooltip delay
                var tooltipTimeout;
                var tooltipDelay = 300;

              // Bind hover event to movie names
              $('.movie-name').hover(
                function() 
                {
                  clearTimeout(tooltipTimeout);
                  var movieId = $(this).data('movie-id');
                  tooltipTimeout = setTimeout(function() 
                  {
                    getMovieOverview(movieId, function(overview) 
                    {
                      tooltip.text(overview).show();
                    });
                  }, tooltipDelay);
                },
                function() 
                {
                  clearTimeout(tooltipTimeout);
                  tooltip.hide();
                }
              );

              // Update the tooltip position on mousemove
              $(document).mousemove(function(e) 
              {
                tooltip.css({ top: e.pageY + 10, left: e.pageX + 10 });
              });
            });

            </script>
            </div>
    </body>

    <footer>
        <div class="footer-container">
          <p class="rights-text">© 2023 Created By <b>COMP3753</b>  All Rights Reserved.</p>
        </div>
 
        <div class="right-col">
          <h1>Our Newsletter</h1>
          <div class="border"></div>
          <p>Enter Your Email to get our news and updates.</p>
          <form action="" class="newsletter-form">
            <input type="text" class="txtb" placeholder="Enter Your Email">
            <input type="submit" class="btn" value="submit">
          </form>
        </div>
    </footer>

</html>
