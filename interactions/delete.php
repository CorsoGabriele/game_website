<?php
	
	session_start();
	
	if(isset($_POST['deleteb'])){
		
		include_once 'database.php';
		
		// select the user to store his data
		$sql = "SELECT * FROM blocks WHERE user='" .$_SESSION['Name'] ."' FOR UPDATE";
				
		try{
			mysqli_autocommit($conn, false);
			$res = mysqli_query($conn, $sql);
			
			if(!$res){
				throw new Exception();
			}
			
			//mysqli_commit($conn);
		} catch(Exception $e){
			mysqli_rollback($conn);
			mysqli_close($conn);
			mysqli_free_result($res);
			header("Location: ../userpage.php?delete1queryfailed");
			exit();
		}
		
		$d = mysqli_num_rows($res);
		
		if($d > 0){
			
			$d--;
		
			$sql = "DELETE FROM blocks WHERE user='" .$_SESSION['Name'] ."' AND number='" .$d ."'";
			
			try{
				mysqli_autocommit($conn, false);
				$res = mysqli_query($conn, $sql);
				
				if(!$res){
					throw new Exception();
				}
				
				mysqli_commit($conn);
			} catch(Exception $e){
				mysqli_rollback($conn);
				mysqli_close($conn);
				mysqli_free_result($res);
				header("Location: ../userpage.php?deletequeryfailed");
				exit();
			}
			
			header("Location: ../userpage.php?deletesuccess");
			exit();
			
		}
		
		header("Location: ../userpage.php?noelement");
		exit();
		
	}else {
		header("Location: ../userpage.php");
		exit();
	}
		
?>