<?php
    require 'db_connect.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Main page</title>
    <link rel="stylesheet" href="design/style.css">
</head>
<body>
    <div id="table_wrapper">
        <div id="table_scroll">
            <table>
                <tr>
                    <td>ID</td>
                    <td>Name</td>
                    <td>Phone</td>
                    <td>Age</td>
                    <td>Operations</td>
                </tr>
                <?php
                $persons = R::findAll('person');

                foreach ($persons as $person) {
                    echo "<tr>";
                    echo "<td>$person->id</td>";
                    echo "<td>" . $person->first_name . " " . $person->last_name . "</td>";
                    echo "<td>$person->phone</td>";
                    echo "<td>$person->age</td>";
                    echo "<td><button class='button' type='button'>edit</button>
                          <button class='button' type='button'>delete</button></td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
