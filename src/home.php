<?php include "dbinfo.inc"; ?>
<html>
<body>
<meta charset="utf-8">
<h1>My Books</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) die(mysqli_connect_error());
  $connection->set_charset('utf8');	

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the BOOKS table exists. */
  VerifyBooksTable($connection, DB_DATABASE);

  if(!empty($_POST)){
      $book_title = htmlentities($_POST['TITLE']);
      $book_author = htmlentities($_POST['AUTHOR']);
      $book_owner = htmlentities($_POST['OWNER']);
      $book_translator = htmlentities($_POST['TRANSLATOR']);
      $book_ISBN = htmlentities($_POST['ISBN']);
      $book_year = htmlentities($_POST['YEAR']);
      $book_publisher = htmlentities($_POST['PUBLISHER']);
      if (strlen($book_ISBN)) {
        AddBook($connection, $book_ISBN, $book_author, $book_title, $book_translator, $book_publisher, $book_year, $book_owner);
      }
  }
?>


<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>ISBN</td>
      <td>AUTHOR</td>
      <td>TITLE</td>
      <td>TRANSLATOR</td>
      <td>PUBLISHER</td>
      <td>YEAR</td>
      <td>OWNER</td>    
    </tr>
    <tr>
      <td>
        <input type="text" name="ISBN" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="AUTHOR" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="TITLE" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="TRANSLATOR" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="PUBLISHER" maxlength="45" size="30" />
      </td>
      <td>
        <input type="text" name="YEAR" maxlength="4" size="30" />
      </td>      
      <td>
	<input type="text" name="OWNER" maxlength="10" size="30" />
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ISBN</td>
    <td>AUTHOR</td>
    <td>TITLE</td>
    <td>TRANSLATOR</td>
    <td>PUBLISHER</td>
    <td>YEAR</td> 
    <td>OWNER</td> 
</tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM Books");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>",
       "<td>",$query_data[3], "</td>",
       "<td>",$query_data[4], "</td>",
       "<td>",$query_data[5], "</td>",
       "<td>",$query_data[6], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add a book to the table. */
function AddBook($connection, $isbn, $author, $title, $translator, $publisher, $year, $owner) {
   $i = mysqli_real_escape_string($connection, $isbn);
   $a = mysqli_real_escape_string($connection, $author);
   $tt = mysqli_real_escape_string($connection, $title);
   $tr = mysqli_real_escape_string($connection, $translator);
   $p = mysqli_real_escape_string($connection, $publisher);
   $y = mysqli_real_escape_string($connection, $year);
   $o = mysqli_real_escape_string($connection, $owner);
 


   $query = "INSERT INTO Books (ISBN, Author, Title, Translator, Publisher, Year, Owner) VALUES ('$i', '$a', '$tt', '$tr', '$p', '$y', '$o');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding book data.</p>");
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
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>                        
