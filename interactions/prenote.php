<?php
	
	session_start();
	include_once 'database.php';
	include_once '../dimensions.php';
	
	$table = array();
	for($i=0; $i < $row; $i++){
		$table[$i] = array();
		for($j=0; $j < $col; $j++){
			$table[$i][$j] = 0;
		}
	}
	
	$error = false;
	
	if(isset($_POST['accept'])){
		
		$sr = $_POST['sr'];
		$sc = $_POST['sc'];
		$er = $_POST['er'];
		$ec = $_POST['ec'];
		
		// make the start and the end block in the right order
		if($er < $sr){
			$temp = $er;
			$er = $sr;
			$sr = $temp;
		}
		
		if($ec < $sc){
			$temp = $ec;
			$ec = $sc;
			$sc = $temp;
		}
		
		// check if the input is wrong
		if($sr == NULL || $sc == NULL || $er == NULL || $ec == NULL){
			mysqli_close($conn);
			echo '<script> alert("Nothing selected");';
			echo'window.location.href="../userpage.php";</script>';
			exit();
		} else{
			
			if($sr == $er){
				
				// the element is of a not valid 
				if(($ec - $sc) != ($leng-1)){
					mysqli_close($conn);
					echo '<script> alert("Block not valid, wrong dimension");';
					echo'window.location.href="../userpage.php";</script>';
					exit();
				}
				
			} else{
				if($sc == $ec){
					
					// the element is of a not valid 
					if(($er - $sr) != ($leng-1)){
						mysqli_close($conn);
						echo '<script> alert("Block not valid, wrong dimension");';
						echo'window.location.href="../userpage.php";</script>';
						exit();
					}
					
				} else{
					mysqli_close($conn);
					echo '<script> alert("Block not valid");';
					echo'window.location.href="../userpage.php";</script>';
					exit();
				}
			}
			
			$query = "SELECT * FROM blocks FOR UPDATE";
	
			// selecting all the members
			try{
				mysqli_autocommit($conn, false);
				$res = mysqli_query($conn, $query);
				
				if(!$res){
					throw new Exception();
				}
				
				//mysqli_commit($conn);
			} catch(Exception $e){
				mysqli_rollback($conn);
				mysqli_close($conn);
				echo '<script> alert("Server error");';
				echo'window.location.href="../userpage.php";</script>';
				exit();
			}
			
			$usrprenot = 0;
			
			// for each element in the database store it in the matrix
			for($i=0; $i < mysqli_num_rows($res); $i++){
				$blk = mysqli_fetch_assoc($res);
				
				// count the prenotation of the user
				if($_SESSION['Name'] == $blk['user']){
					$usrprenot++;
				}
				
				if($blk['rowstart'] == $blk['rowend']){
					
					for($j=0; $j < $leng; $j++){
						$table[$blk['rowstart']][($blk['colstart']+$j)]++;
					}
					
				} else{
					
					for($j=0; $j < $leng; $j++){
						$table[($blk['rowstart']+$j)][$blk['colstart']]++;
					}
				}
			}
			
			// insert the element in the table
			if($sr == $er){
					
				for($j=0; $j < $leng; $j++){
					$table[$sr][($sc+$j)]++;
				}
				
			} else{
					
				for($j=0; $j < $leng; $j++){
					$table[($sr+$j)][$sc]++;
				}
			}
			
			// check for overlap
			for($i=0; $i < $row; $i++){
				for($j=0; $j < $col; $j++){
					
					// there is an overlap
					if($table[$i][$j] == 2){
						mysqli_rollback($conn);
						mysqli_close($conn);
						echo '<script> alert("Invalid position");';
						echo'window.location.href="../userpage.php";</script>';
						exit();
					}
				}
			}
			
			if($sr == $er){
					
				if(($sr-1) >= 0 && ($sc-1) >= 0){
					// top left of the first block
					if($table[($sr-1)][($sc-1)] == 1){
						$error = true;
					}
				}
				
				if(($sc-1) >= 0){
					// left of the first block
					if($table[$sr][($sc-1)] == 1){
						$error = true;
					}
				}
				
				if(($sr+1) < $row && ($sc-1) >= 0){
					// bottom left of the first block
					if($table[($sr+1)][($sc-1)] == 1){
						$error = true;
					}
				}
				
				if(($er-1) >= 0 && ($ec+1) < $col){
					// top right of the second block
					if($table[($er-1)][($ec+1)] == 1){
						$error = true;
					}
				}
				
				if(($ec+1) < $col){
					// right of the second block
					if($table[($er)][($ec+1)] == 1){
						$error = true;
					}
				}
				
				if(($er+1) < $row && ($ec+1) < $col){
					// bottom right of the second block
					if($table[($er+1)][($ec+1)] == 1){
						$error = true;
					}
				}
				
				
				// check top and bottom of the blocks
				for($j=0; $j < $leng; $j++){
					
					if(($sr-1) >= 0 && ($sc+$j) < $col){
						// top of the block
						if($table[($sr-1)][($sc+$j)] == 1){
							$error = true;
						}
					}
					
					if(($sr+1) < $row && ($sc+$j) < $col){
						// bottom of the block
						if($table[($sr+1)][($sc+$j)] == 1){
							$error = true;
						}
					}
					
				}
				
			} else{
				
				if(($sr-1) >= 0 && ($sc-1) >= 0){
					// top left of the first block
					if($table[($sr-1)][($sc-1)] == 1){
						$error = true;
					}
				}
				
				if(($sr-1) >= 0){
					// top of the first block
					if($table[($sr-1)][$sc] == 1){
						$error = true;
					}
				}
				
				if(($sr-1) >= 0 && ($sc+1) < $col){
					// top right of the first block
					if($table[($sr-1)][($sc+1)] == 1){
						$error = true;
					}
				}
				
				if(($er+1) < $row && ($sc-1) >= 0){
					// bottom left of the second block
					if($table[($er+1)][($sc-1)] == 1){
						$error = true;
					}
				}
				
				if(($er+1) < $row){
					// bottom of the second block
					if($table[($er+1)][$sc] == 1){
						$error = true;
					}
				}
				
				if(($er+1) < $row && ($sc+1) < $col){
					// bottom right of the second block
					if($table[($er+1)][($sc+1)] == 1){
						$error = true;
					}
				}
				
				for($j=0; $j < $leng; $j++){
					
					if(($sc-1) >= 0){
						// left of the block
						if($table[($sr+$j)][($sc-1)] == 1){
							$error = true;
						}
					}
					
					if(($sc+1) < $col){
						// right of the block
						if($table[($sr+$j)][($sc+1)] == 1){
							$error = true;
						}
					}
					
				}
			}
			
			if($error){
				mysqli_rollback($conn);
				mysqli_close($conn);
				echo '<script> alert("Invalid position");';
				echo'window.location.href="../userpage.php";</script>';
				exit();
			}
			
			
			$query = "INSERT INTO blocks (user, rowstart, colstart, rowend, colend, number) VALUES ('" .$_SESSION['Name'] ."', '" .$sr ."', '" .$sc ."', '" .$er ."', '" .$ec ."', '" .$usrprenot ."')";
	
			try{
				mysqli_autocommit($conn, false);
				$res = mysqli_query($conn, $query);
				
				if(!$res){
					throw new Exception();
				}
				
				mysqli_commit($conn);
			} catch(Exception $e){
				mysqli_rollback($conn);
				mysqli_close($conn);
				echo '<script> alert("Server error");';
				echo'window.location.href="../userpage.php";</script>';
				exit();
			}
			
			echo '<script> alert("Element Stored");';
			echo'window.location.href="../userpage.php";</script>';
			exit();
			
		}
		
	} else {
		echo '<script> alert("Booking failed");';
		echo'window.location.href="../userpage.php";</script>';
		exit();
	}
	
?>