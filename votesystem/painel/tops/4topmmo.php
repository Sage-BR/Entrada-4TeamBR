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

// Faz a requisição para a API.
$url = "https://top.4teambr.com/api.php?name=" . $row->top_id . "&ip=" . get_client_ip();
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$pagina = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Inicializa variáveis padrão.
$can_vote = false;
$data_modificada = '0000-00-00 00:00:00';
$tops_voted = array_replace($tops_voted ?? [], [$i => [1, $data_modificada]]);

if ($http_code === 200) {
    $json = json_decode($pagina, true);
    
    if ($json !== null && isset($json['voted'])) {
        // Verifica se o usuário votou.
        $voted = intval($json['voted']);
        $vote_date = $json['vote_date'];

        // Se o usuário votou recentemente, calcula o tempo até poder votar de novo.
        if ($voted === 1 && !empty($vote_date)) {
            $hoursToVoteAgain = 12;
            $data_modificada = date("Y-m-d H:i:s", strtotime($vote_date . " + {$hoursToVoteAgain} hours"));
        } else {
            // Permite votar se não há registro de voto ou o tempo de espera já passou.
            $can_vote = true;
        }
    } else {
        // Se não há resposta adequada da API, permite votar.
        $can_vote = true;
    }
} else {
    // Se houve erro na requisição, permite votar.
    $can_vote = true;
}

$dataAtual = new DateTime();
$dataModificada = new DateTime($data_modificada);

if ($can_vote || $dataModificada <= $dataAtual) {
    // Exibe botão de votação.
    ?>
    <div style="width:87px; height:47px; border:1px solid #999; margin-top:5px; margin-left:5px; float:left;">
        <a href="https://top.4teambr.com/index.php?a=in&u=<?= htmlspecialchars($row->top_id); ?>" target="_blank">
            <img src="images/buttons/<?= htmlspecialchars($row->top_img); ?>" 
                 title="4TOP Private Servers" 
                 border="0" width="87" height="47">
        </a>
    </div>
    <?php
} else {
    // Exibe contador usando a lógica sugerida.
    $data_voto = explode("-", substr(str_replace(" ", "", $data_modificada), 0, 10));
    $hora_voto = explode(":", substr(str_replace(" ", "", $data_modificada), 10, 19));

    $tops_voted = array_replace($tops_voted, [$i => [1, $data_modificada]]);
    ?>
    <script language="javascript">
        atualizaContador(
            <?= htmlspecialchars($row->id); ?>,
            <?= htmlspecialchars($data_voto[0]); ?>,
            <?= htmlspecialchars($data_voto[1]); ?>,
            <?= htmlspecialchars($data_voto[2]); ?>,
            <?= htmlspecialchars($hora_voto[0]); ?>,
            <?= htmlspecialchars($hora_voto[1]); ?>,
            <?= htmlspecialchars($hora_voto[2]); ?>
        );
    </script>
    <div style="background:url(images/buttons/<?php echo $row->top_img; ?>); background-repeat: no-repeat; background-size: 87px 47px; width:87px; height:47px; border:1px solid #999; margin-top:5px; margin-left:5px; float:left;">
        <div style="width:87px; height:47px; font-size:10px; font-family:Arial; background: rgba(0, 0, 0, 0.7); text-shadow:1px 1px #000; font-weight:bold; color: #fff; text-align: center;">
            <?= $language_05; ?><br>
            <font size="3"><span id="contador<?= htmlspecialchars($row->id); ?>"></span></font><br>
            <?= $language_06; ?>
        </div>
    </div>
    <?php
}
?>
