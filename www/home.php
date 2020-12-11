<?php include "dbinfo.inc"; ?>
<html>
<body>
<meta charset="utf-8">
<h1>My Books</h1>

<style>
.book_forms {
    padding-left: 0px;
    padding-right: 70px;
}
</style>

<div style="display: inline-flex">
		<div class="book_forms">
			<form action="/add_book.php" method="post" accept-charset=utf-8>
				<h3> Add a Book to the database </h3><br>
              <label for="ISBN">ISBN</label><br>
              <input style="display: flex" type="text" id="ISBN" name="ISBN" placeholder="Insert ISBN here">
              <select name="book_status" id="book_status">
                  <option value="Wishlist">Wishlist</option>
                  <option value="Owned">Owned</option>
                  <option value="Read">Read</option>
              </select>
				<input type="submit" value="Insert">
			</form>
    </div>
    <div class="book_forms">
      <form action="/update_book.php" method="post" accept-charset=utf-8>
				<h3> Update a book's status </h3><br>
              <label for="ISBN">ISBN</label><br>
              <input style="display: flex" type="text" id="ISBN" name="ISBN" placeholder="Insert ISBN here">
              <select name="book_status" id="book_status">
                  <option value="Wishlist">Wishlist</option>
                  <option value="Owned">Owned</option>
                  <option value="Read">Read</option>
              </select>
				<input type="submit" value="Update">
      </form>
    </div>
    <div class="book_forms">
      <form action="/delete_book.php" method="post" accept-charset=utf-8>
				<h3>Delete a book from the database </h3><br>
              <label for="ISBN">ISBN</label><br>
              <input style="display: flex" type="text" id="ISBN" name="ISBN" placeholder="Insert ISBN here">
              <input type="submit" value="Delete">
    </div>
</div>

<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) die(mysqli_connect_error());
  $connection->set_charset('utf8');	

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the BOOKS table exists. */
  VerifyBooksTable($connection, DB_DATABASE);
?>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ISBN</td>
    <td>AUTHORS</td>
    <td>TITLE</td>
    <td>PUBLISHER</td>
    <td>YEAR</td> 
    <td>PAGES</td> 
    <td>RATING</td>
    <td>TOTAL RATINGS</td>
    <td>STATUS</td>
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
       "<td>",$query_data[6], "</td>",
       "<td>",$query_data[7], "</td>",
       "<td>",$query_data[8], "</td>";
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
