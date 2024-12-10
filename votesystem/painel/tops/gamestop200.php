<?php
//=======================================================================\\
//  ## ####### #######                                                   \\
//  ## ##      ##   ##                                                   \\
//  ## ##      ## ####  |\  | |¯¯¯ ¯¯|¯¯ \      / |¯¯¯| |¯¯¯| | / |¯¯¯|  \\
//  ## ##      ##       | \ | |--    |    \    /  | | | | |_| |<   ¯\_   \\
//  ## ####### ##       |  \| |___   |     \/\/   |___| | |\  | \ |___|  \\
// --------------------------------------------------------------------- \\
//       Brazilian Developer / Website: http://www.icpfree.com.br       \\
//                 Email & Skype: ivan1507@gmail.com.br                  \\
//=======================================================================\\

// Define constante e IP do cliente
define("GamesTop200", gethostbyname("gamestop200.com"));
$ip_request = $_SERVER['REMOTE_ADDR'];

// Verifica a conexão com o site listado
$top_url = str_replace(["https://", "http://"], "", $row->top_url);
if (@fsockopen($top_url, 80, $errno, $errstr, 30)) {
    @header('Content-Type: text/html; charset=utf-8');

    // Faz a requisição para a API de verificação de votos
    $api_url = "https://api.gamestop200.com/check/{$row->top_id}/{$ip_request}";
    $pagina = @file_get_contents($api_url);
    $pagina = json_decode($pagina);

    // Verifica se a API retornou uma data válida
    $data_modificada = !empty($pagina->date) 
        ? date("Y-m-d H:i:s", strtotime($pagina->date . " + 12 hours")) 
        : '0000-00-00 00:00:00';

    // Verifica se o tempo para votar novamente já passou
    if (strtotime($data_modificada) >= strtotime(date('Y-m-d H:i:s'))) {
        // Calcula a data e a hora do último voto
        $data_voto = explode("-", substr($data_modificada, 0, 10));
        $hora_voto = explode(":", substr($data_modificada, 11));

        // Atualiza a lista de votos
        $tops_voted = array_replace($tops_voted, [$i => [1, $data_modificada]]);
        ?>
        <script>
            atualizaContador(
                <?php echo $row->id; ?>,
                <?php echo $data_voto[0]; ?>,
                <?php echo $data_voto[1]; ?>,
                <?php echo $data_voto[2]; ?>,
                <?php echo $hora_voto[0]; ?>,
                <?php echo $hora_voto[1]; ?>,
                <?php echo $hora_voto[2]; ?>
            );
        </script>
        <div style="background:url(images/buttons/<?php echo $row->top_img; ?>); 
                    background-repeat: no-repeat; 
                    background-size: 87px 47px; 
                    width:87px; height:47px; 
                    border:1px solid #999; 
                    margin-top:5px; 
                    margin-left:5px; 
                    float:left;">
            <div style="width:89px; height:49px; 
                        font-size:10px; 
                        font-family:Arial; 
                        background: rgba(0,0,0,0.7); 
                        text-shadow:1px 1px #000; 
                        font-weight:bold;">
                <?php echo $language_05; ?><br>
                <font size="3">
                    <span id="contador<?php echo $row->id; ?>"></span>
                </font><br>
                <?php echo $language_06; ?>
            </div>
        </div>
        <?php
    } else {
        // Exibe o botão de votação se o tempo já passou
        ?>
        <div style="width:87px; height:47px; 
                    border:1px solid #999; 
                    margin-top:5px; 
                    margin-left:5px; 
                    float:left;">
            <a href="https://www.gamestop200.com/details/<?php echo $row->top_id; ?>/vote" 
               target="_blank">
                <img src="images/buttons/<?php echo $row->top_img; ?>" 
                     title="Extreme Game Sites" 
                     border="0" 
                     width="87" 
                     height="47">
            </a>
        </div>
        <?php
    }
} else {
    // Em caso de falha na conexão, configura um valor padrão
    $tops_voted = array_replace($tops_voted, [$i => [1, '0000-00-00 00:00:00']]);
}
?>
