<?php
	
	include_once './dimensions.php';
	
	echo '<table class="grid">';
	
	for($i=0; $i < $row; $i++){
		
		echo '<tr>';
		
		for($j=0; $j < $col; $j++){
			
			$query = "SELECT * FROM blocks WHERE (rowstart='" .$i ."' AND colstart<='" .$j ."' AND colend>='" .$j ."') OR 
													(colstart='" .$j ."' AND rowstart<='" .$i ."' AND rowend>='" .$i ."')";
	
			// checking if the block is black
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
				mysqli_free_result($res);
				header("Location: userpage.php?indexqueryfailed");
				exit();
			}
			
			$r = mysqli_num_rows($res);
			
			if($r == 1){
				echo '<td class="blocko"></td>';
			} else{
				echo '<td class="blockv"></td>';
			}
			
		}
		
		echo '</tr>';
		
	}
	
	echo '</table>';
	
?>