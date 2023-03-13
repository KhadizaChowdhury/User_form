<!DOCTYPE html>
<html>
<head>
	<title>User List</title>
	<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
			padding: 5px;
		}
	</style>
</head>
<body>
	<h2>User List</h2>
	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Profile Picture</th>
			</tr>
		</thead>
		<tbody>
			<?php
            $file = fopen( 'users.csv', 'r' );
            while (  ( $row = fgetcsv( $file ) ) !== false ) {
                echo '<tr>';
                echo '<td>' . $row[0] . '</td>';
                echo '<td>' . $row[1] . '</td>';
                echo '<td><img src="'.$row[2].'" height="100"></td>';
                echo '</tr>';
            }
            fclose( $file );
            ?>
        </tbody>
        </table>

        </body>
        </html>