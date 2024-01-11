<?php
  if (isset($_POST['movie_id'])) 
  {
    $movie_id = $_POST['movie_id'];

    $host = "localhost";
    $user = "postgres";
    $password = "yanncuando";
    $dbname = "MovieDatabase";

    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT overview FROM movies.movie WHERE movie_id = :movie_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) 
    {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      echo $row['overview'];
    } else 
    {
      echo "Overview not found.";
    }

    $conn = null;

  }

?>
