<?php
//=======================================================================\\
//  ## ####### #######                                                   \\
//  ## ##      ##   ##                                                   \\
//  ## ##      ## ####  |\  | |¯¯¯ ¯¯|¯¯ \      / |¯¯¯| |¯¯¯| | / |¯¯¯|  \\
//  ## ##      ##       | \ | |--    |    \    /  | | | | |_| |<   ¯\_   \\
//  ## ####### ##       |  \| |___   |     \/\/   |___| | |\  | \ |___|  \\
// --------------------------------------------------------------------- \\
//       Brazillian Developer / WebSite: http://www.icpfree.com.br       \\
//=======================================================================\\

// Faz requisição para a API.
if (@fsockopen(str_replace("https://", "", str_replace("http://", "", $row->top_url)), 80, $errno, $errstr, 30)) {
    @header('Content-Type: text/html; charset=utf-8');
    
    // Construindo a URL
    $url = "https://www.top100arena.com/check_ip/" . $row->top_id . "?ip=" . get_client_ip();

    // Usando cURL para consultar a API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Validando a resposta da API
    if ($http_code == 200) { // Sucesso na chamada
        $data = json_decode($response, true);

        if (isset($data['voted']) && $data['voted'] === true && !isset($_COOKIE["top100arena"])) {
            ?>
            <script language="javascript">
                SetCookie('top100arena');
            </script>
            <?php
        }
    }

    if (isset($_COOKIE["top100arena"])) {
        $data_modificada = pega_cookie($_COOKIE["top100arena"]);
    } else {
        $data_modificada = '0000-00-00 00:00:00';
    }

    if (strtotime($data_modificada) >= strtotime(date('Y-m-d H:i:s'))) {
        $data_voto = explode("-", substr(str_replace(" ", "", $data_modificada), 0, 10));
        $hora_voto = explode(":", substr(str_replace(" ", "", $data_modificada), 10, 19));
        $tops_voted = array_replace($tops_voted, array($i => array(1, $data_modificada)));
        ?>
        <script language="javascript">
            atualizaContador(<?php echo $row->id; ?>, <?php echo $data_voto[0]; ?>, <?php echo $data_voto[1]; ?>, <?php echo $data_voto[2]; ?>, <?php echo $hora_voto[0]; ?>, <?php echo $hora_voto[1]; ?>, <?php echo $hora_voto[2]; ?>);
        </script>
        <div style='background:url(images/buttons/<?php echo $row->top_img; ?>); background-repeat: no-repeat; background-size: 87px 47px; width:87px; height:47px; border:1px solid #999; margin-top:5px; margin-left:5px; float:left;'>
            <div style='width:89px; *width:87px; _width:87px; height:49px; *height:47px; _height:47px; font-size:10px; font-family:Arial; background: rgba(0,0,0,0.7); text-shadow:1px 1px #000; font-weight:bold;'>
                <?php echo $language_05; ?><br><font size='3'><span id='contador<?php echo $row->id; ?>'></span></font><br><?php echo $language_06; ?>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div style='width:87px; height:47px; border:1px solid #999; margin-top:5px; margin-left:5px; float:left;'>
            <a href='https://www.top100arena.com/listing/<?php echo $row->top_id; ?>/vote' target='_blank'><img src='images/buttons/<?php echo $row->top_img; ?>' title='Lineage 2 Private Servers' border='0' width='87' height='47' onClick="javascript:SetCookie('top100arena');"></a>
        </div>
        <?php
    }
} else {
    $tops_voted = array_replace($tops_voted, array($i => array(1, '0000-00-00 00:00:00')));
}
?>