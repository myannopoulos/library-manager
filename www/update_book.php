<html>
<body>
<?php
include "dbinfo.inc"; 
$isbn = $_POST['ISBN'];
$isbn = str_replace("-", "", $isbn);
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

    if (mysqli_connect_errno()) die(mysqli_connect_error());
    $connection->set_charset('utf8');	
  
    $database = mysqli_select_db($connection, DB_DATABASE);
  
    /* Ensure that the BOOKS table exists. */
    VerifyBooksTable($connection, DB_DATABASE);
    $query = "UPDATE Books SET status='". $_POST["book_status"] . "' WHERE ISBN=" . $isbn;
    if(!mysqli_query($connection, $query)) echo("<p>Error updating book status.</p>");
    else echo("<p>Operation complete!</p>");
?>

<a href="home.php">
   <button>Return to home</button>
</a>

<?php
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

</body>
<html>