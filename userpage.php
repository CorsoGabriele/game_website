<?php
	session_start();
	include_once 'timeout.php';
	include_once 'interactions/database.php';
	
	if(!isset($_SESSION['Name'])){
		header("Location: index.php");
	}
?>

<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="style.css">

		<!-- Bootstrap CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		
		<title>Personal Page</title>
	</head>
	
	<body>
	
		<script>
		
			function openNav() {
				document.getElementById("mySidenav").style.width = "15%";
				document.getElementById("main").style.marginLeft = "10%";
				document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
			}

			function closeNav() {
				document.getElementById("mySidenav").style.width = "0";
				document.getElementById("main").style.marginLeft= "0";
				document.body.style.backgroundColor = "white";
			}
		
		</script>
		
		<div id="mySidenav" class="sidenav">
		
			<?php
			
				if($_SERVER['HTTPS'] != "on"){
					header("Location: https://" .$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI']);
				}
				
				if(isset($_SESSION['Name'])){
					echo '<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
							<a href="index.php">Home</a>
							<form id="logoutb" action="interactions/logout.php" method="POST">
								<a>
									<input name="logout" type="submit" value="Logout">
								</a>
							</form>';
				}
			?> 
			
		</div>

		<div id="main">
			<span style="font-size:50px;cursor:pointer" onclick="openNav()">&#9776;</span>
		</div>
		
		<h1 id="title" class="text-center font-weight-bold"><span>Personal Page</span></h1> 
		
		<?php
			include_once './userpagetable.php';
		?>
		
		<br><br><br>
		
		<div id="choice" class="text-center">
			<form action="./interactions/prenote.php" method="POST">
				<input type="hidden" id="startr" name="sr" value=NULL readonly>
				<input type="hidden" id="startc" name="sc" value=NULL readonly>
				<input type="hidden" id="endr" name="er" value=NULL readonly>
				<input type="hidden" id="endc" name="ec" value=NULL readonly>
				<button name="accept" type="submit" class="btn btn-primary" method="POST">Accept</button>
			</form>
			
			<button class="btn btn-primary" onclick="uncolor()">Undo</button>
			
			<form action="./interactions/delete.php" method="POST">
				<button name="deleteb" type="submit" class="btn btn-primary" method="POST">Delete</button>
			</form>
		
		</div>
		
		<script>
		
			blockselected = 0;
			
			leng =  <?php echo $leng ?>;
			
			function prova(id){
				
				sp = id.split("-");
				
				// store the row and the column of the pressed button
				row = Number(sp[1]);
				col = Number(sp[2]);
				
				// increment the number of pressed elements
				blockselected++;
				
				// if were pressed less then 2 elements color the element
				if(blockselected < 3){
					if(document.getElementById(id).className == "block"){
						document.getElementById(id).className = 'blockp';
					}
				}
				
				// store the position of the first pressed block
				if(blockselected == 1){
					document.getElementById("startr").value = row;
					document.getElementById("startc").value = col;
				}
				
				// store the position of the second pressed block
				if(blockselected == 2){
					document.getElementById("endr").value = row;
					document.getElementById("endc").value = col;
					
					// retrieve the values of the selected blocks
					srow = document.getElementById("startr").value;
					scol = document.getElementById("startc").value;
					erow = document.getElementById("endr").value;
					ecol = document.getElementById("endc").value;
					
					// if the element is horizzontal
					if(srow == erow){
						
						// calculate the distance
						dist = ecol - scol;
						
						if(dist < 0){
							dist = dist * (-1);
						}
						
						// if the distance is right color all the other blocks
						if(dist == leng-1){
							
							if(scol < ecol){
								ref = scol;
							} else{
								ref = ecol;
							}
							
							c = check(ref);
							
							if(c){
								color(ref);
							} else{
								alert("The element must not overlap the other blocks");
								erase();
							}
							
							
						} else{
							alert("The element must be long " + leng + " squares");
							erase();
						}
						
					} else{
						// if the element is vertical
						if(scol == ecol){
							
							// calculate the distance
							dist = erow - srow;
							
							if(dist < 0){
								dist = dist * (-1);
							}
							
							if(dist == leng-1){
								
								if(srow < erow){
									ref = srow;
								} else{
									ref = erow;
								}
								
								c = check(ref);
								
								if(c){
									color(ref);
								} else{
									alert("The element must not touch the other blocks");
									erase();
								}
								
							} else{
								alert("The element must be long " + leng + " squares");
								erase();
							}
							
						} else{
							// diagonal blocks
							alert("The blocks must not be diagonal");
							erase();
						}
					}
					
				}
				
			}
			
			// check if there are not black blocks
			function check(ref){
				
				// retrieve the values of the selected blocks
				srow = document.getElementById("startr").value;
				scol = document.getElementById("startc").value;
				erow = document.getElementById("endr").value;
				ecol = document.getElementById("endc").value;
				
				if(srow == erow){
					for(i=1; i < leng-1; i++){
						pos = Number(ref) + Number(i);
						temp = "p-" + srow + "-" + pos;
						if(document.getElementById(temp).className == "blocko" || document.getElementById(temp).className == "blocku"){
							return false;
						}
					}
				} else{
					for(i=1; i < leng-1; i++){
						pos = Number(ref) + Number(i);
						temp = "p-" + pos + "-" + scol;
						if(document.getElementById(temp).className == "blocko" || document.getElementById(temp).className == "blocku"){
							return false;
						}
					}
				}
				
				return true;
			}
			
			function erase(){
				// error input so color of white the selected blocks
				temp = "p-" + document.getElementById("startr").value + "-" + document.getElementById("startc").value;
				document.getElementById(temp).className = "block";
				
				temp = "p-" + document.getElementById("endr").value + "-" + document.getElementById("endc").value;
				document.getElementById(temp).className = "block";
				
				document.getElementById("startr").value = 'NULL';
				document.getElementById("startc").value = 'NULL';
				document.getElementById("endr").value = 'NULL';
				document.getElementById("endc").value = 'NULL';
				
				blockselected = 0;
			}
			
			function color(ref){
				
				// retrieve the values of the selected blocks
				srow = document.getElementById("startr").value;
				scol = document.getElementById("startc").value;
				erow = document.getElementById("endr").value;
				ecol = document.getElementById("endc").value;
				
				if(srow == erow){
					for(i=1; i < leng-1; i++){
						pos = Number(ref) + Number(i);
						temp = "p-" + srow + "-" + pos;
						document.getElementById(temp).className = "blockp";
					}
				} else{
					for(i=1; i < leng-1; i++){
						pos = Number(ref) + Number(i);
						temp = "p-" + pos + "-" + scol;
						document.getElementById(temp).className = "blockp";
					}
				}
				
			}
			
			function uncolor(){
				window.location.href=" userpage.php";
			}
			
			
		</script>
		
		<!-- Optional JavaScript -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>