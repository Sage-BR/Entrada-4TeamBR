<?php
//=======================================================================\\
//  ## ####### #######                                                   \\
//  ## ##      ##   ##                                                   \\
//  ## ##      ## ####  |\  | |¯¯¯ ¯¯|¯¯ \      / |¯¯¯| |¯¯¯| | / |¯¯¯|  \\
//  ## ##      ##       | \ | |--    |    \    /  | | | | |_| |<   ¯\_   \\
//  ## ####### ##       |  \| |___   |     \/\/   |___| | |\  | \ |___|  \\
// --------------------------------------------------------------------- \\
//       Brazillian Developer / WebSite: http://www.icpfree.com.br       \\
//                 Email & Skype: ivan1507@gmail.com.br                  \\
//=======================================================================\\
if($mostra_votos == 1){
	$busca_votos = $conn->prepare("SELECT SUM(votos) AS total FROM icp_votesystem_votos");
	$busca_votos->execute();
	$count = $busca_votos->fetch(PDO::FETCH_ASSOC);
	$t_voto = !empty($count["total"]) ? $count["total"] : 0;
	echo $language_61." ".$t_voto;
}
?>