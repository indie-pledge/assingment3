<?php // sqltest.php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

echo '<link rel="stylesheet" type="text/css" href="assignment3/css/table.css">';
echo '<script src="assignment3/js/checkboxes.js"></script>';

if (isset($_POST['delete'])) {
  foreach ($_POST['delete'] as $isbn) {
    $isbn = get_post($conn, 'isbn');
    $query = "DELETE FROM classics WHERE isbn='$isbn'";
    $result = $conn->query($query);
    if (!$result) echo "DELETE failed: $query<br>" . $conn->error . "<br><br>";
  }
}

if (isset($_POST['author']) && isset($_POST['title']) && isset($_POST['category']) && isset($_POST['year']) && isset($_POST['isbn'])) {
  $author = get_post($conn, 'author');
  $title = get_post($conn, 'title');
  $category = get_post($conn, 'category');
  $year = get_post($conn, 'year');
  $isbn = get_post($conn, 'isbn');
  $query = "INSERT INTO classics VALUES ('$author', '$title', '$category', '$year', '$isbn')";
  $result = $conn->query($query);
  if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
}

echo <<<_END
<form action="sqltest.php" method="post"><pre>
Author <input type="text" name="author">
Title <input type="text" name="title">
Category <input type="text" name="category">
Year <input type="text" name="year">
ISBN <input type="text" name="isbn">
<input type="submit" value="ADD RECORD">
</pre></form>
_END;

echo "<form action='sqltest.php' method='post'>";
echo "<table border='1'><thead><tr><th><input type='checkbox' onclick='check_all(this)'></th><th>Author</th><th>Title</th><th>Category</th><th>Year</th><th>ISBN</th></tr></thead><tbody>";
$query = "SELECT * FROM classics";
$result = $conn->query($query);
if (!$result) die("Database access failed: " . $conn->error);
$rows = $result->num_rows;
for ($j = 0; $j < $rows; ++$j) {
  $result->data_seek($j);
  $row = $result->fetch_array(MYSQLI_NUM);
  echo "<tr><td><input type='checkbox' name='delete[]' value='$row[4]'></td><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td></tr>";
}
echo "</tbody></table>";
echo "<input type='submit' value='DELETE SELECTED RECORDS'>";
echo "</form>";
$result->close();
$conn->close();

function get_post($conn, $var) {
  return $conn->real_escape_string($_POST[$var]);
}
?>
