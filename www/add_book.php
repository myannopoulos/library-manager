<html>
<body>
<?php
include "dbinfo.inc"; 
$isbn = $_POST['ISBN'];
$isbn = str_replace("-", "", $isbn);
$book_status = $_POST['book_status'];
  if(isset($_POST['title'])){
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    if (mysqli_connect_errno()) die(mysqli_connect_error());
    $connection->set_charset('utf8');	
  
    $database = mysqli_select_db($connection, DB_DATABASE);
  
    /* Ensure that the BOOKS table exists. */
    VerifyBooksTable($connection, DB_DATABASE);

    $book_ISBN = $_POST['ISBN'];
    $book_authors = $_POST['authors'];
    $book_title = $_POST['title'];
    $book_publisher = $_POST['publisher'];
    $book_year = $_POST['year'];
    $book_pages = $_POST['pages'];
    $book_rating = $_POST['rating'];
    $book_total_ratings = $_POST['total_ratings'];
    $book_status = $_POST['book_status'];
    AddBook($connection, $book_ISBN, $book_authors, $book_title, $book_publisher, $book_year, $book_pages, $book_rating, $book_total_ratings, $book_status);
    
    mysqli_close($connection);
    exit;
  }
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type = "module">
    import {key} from './key.js'
  $(document).ready(function(){
    var isbn = "<?php echo $isbn ?>";
    var book_status = "<?php echo $book_status ?>";
    var request = new XMLHttpRequest();
    var url='https://www.googleapis.com/books/v1/volumes?q=isbn:' + isbn + '&key=' + key;
    request.open("GET", url);
    request.send();

    request.onreadystatechange = function(){
      if(this.readyState==4 && this.status==200){
        //console.log(request.responseText)
        var result = JSON.parse(request.responseText)["items"][0]["volumeInfo"]
        var authors = result["authors"][0]
        var title = result["title"]
        if(result["subtitle"]) title += " - " + result["subtitle"]
        var publisher = result["publisher"]
        var year = result["publishedDate"]
        var pages = result["pageCount"]
        var rating = result["averageRating"]
        var total_ratings = result["ratingsCount"]
        var i
        for(i = 1; i < result["authors"].length; i++){
            authors += ", " + result["authors"][i]
        }
        $.ajax({
            type : 'post',  
            data : { 
                    'ISBN' : isbn, 
                    'authors' : authors, 
                    'title' : title,
                    'publisher' : publisher, 
                    'year' : year, 
                    'pages' : pages,
                    'rating' : rating, 
                    'total_ratings' : total_ratings,
                    'book_status' : book_status
                  },
            success: function(response){  
                     alert('Operation complete!')
                    }
        });
      }
      else {
          console.log(this.status)
      }
    }
  });
</script>




<?php

/* Add a book to the table. */
function AddBook($connection, $isbn, $authors, $title, $publisher, $year, $pages, $rating, $total_ratings, $status) {
   $isbn = mysqli_real_escape_string($connection, $isbn);
   $authors = mysqli_real_escape_string($connection, $authors);
   $title = mysqli_real_escape_string($connection, $title);
   $publisher = mysqli_real_escape_string($connection, $publisher);
   $year = mysqli_real_escape_string($connection, $year);
   $pages = mysqli_real_escape_string($connection, $pages);
   $rating = mysqli_real_escape_string($connection, $rating);
   $total_ratings = mysqli_real_escape_string($connection, $total_ratings);
   $status = mysqli_real_escape_string($connection, $status);



   $query = "INSERT INTO Books (ISBN, Authors, Title, Publisher, Year, Pages, Rating, Total_ratings, Status) 
             VALUES ('$isbn', '$authors', '$title', '$publisher', '$year', '$pages', '$rating', '$total_ratings', '$status');";
   echo $query;
   if(!mysqli_query($connection, $query)) echo("<p>Error adding book data.</p>");
   else echo("<p> Operation complete!</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyBooksTable($connection, $dbName) {
  if(!TableExists("Books", $connection, $dbName))
  {
	echo "ERROR: Table does not exist";
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $tb = mysqli_real_escape_string($connection, $tableName);
  $db = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$tb' AND TABLE_SCHEMA = '$db'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>         

<a href="home.php">
   <button>Return to home</button>
</a>


</body>
</html>
