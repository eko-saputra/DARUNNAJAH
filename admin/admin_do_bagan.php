<?php 
	session_start();
	if(!isset($_SESSION['pwd']))
	{
		header('location:login.php');
	}
	require_once ("includes/connection.php");
	
	$golongan = mysqli_real_escape_string($koneksi, $_POST["golongan"]);
	$jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST["jenis_kelamin"]);
	$kelas_tanding = mysqli_real_escape_string($koneksi, $_POST["kelas_tanding"]);

	$sql = "SELECT * FROM undian 
						INNER JOIN peserta ON peserta.ID_peserta=undian.id_peserta 
						INNER JOIN kelastanding ON peserta.kelas_tanding_FK=kelastanding.ID_kelastanding
						WHERE peserta.golongan = '$golongan' 
						AND peserta.jenis_kelamin = '$jenis_kelamin'
						AND peserta.kelas_tanding_FK = '$kelas_tanding'
						ORDER BY undian.no_undian ASC";
	$result= mysqli_query($koneksi,$sql);


	$data=array();
	$team_name=array();
	while($row = mysqli_fetch_assoc($result)) {
		array_push($team_name, array('seed'=>$row['kontingen'],'name'=>$row['no_undian'].' . '.$row['nm_lengkap']));
		$no = $row['no_undian'];
	} 

	$data['teams']=$no;
	$data['teamNames']=$team_name;
	echo json_encode($data);
?>