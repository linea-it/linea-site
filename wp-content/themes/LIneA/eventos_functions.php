<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

function get_eventos_por_ano($posts) {
    $years = array();
    foreach($posts as $post) {
        $years[date('Y', strtotime($post['data_inicial']))][] = $post;
    }
    return $years;
}
function get_eventos($con, $tipo, $login){
  return get_eventos_envolvimento($con, '', $tipo, $login);
}

function get_eventos_envolvimento($con, $envolvimento, $tipo, $login) {
  $sql = 'SELECT * FROM eventos WHERE ';
  if ($envolvimento != ''){
    $sql .= 'envolvimento = :envolvimento AND ';
  }
  $sql .= '(tipo = :tipo OR tipo = "Ambos") ';
  if (!$login){
    $sql .= "AND publico = TRUE ";
  }
  $sql .= "ORDER BY data_inicial DESC";

  $prep = $con->prepare($sql);
  $prep->bindParam(':envolvimento', $envolvimento, PDO::PARAM_STR);
  $prep->bindParam(':tipo', $tipo, PDO::PARAM_STR);
  $prep->execute();
  $result = $prep->fetchAll();
  return $result;
}

function get_org_porte($organizacao, $porte) {
  $array_porte = array();
  foreach ($organizacao as $row_org) {
    if ($row_org['porte'] == $porte) {
      array_push($array_porte, $row_org);
    }
  }
  return $array_porte;
}

function show_evento($row_evento, $login_adm) {
  $data_inicial = strtotime($row_evento['data_inicial']);
  $data_final = strtotime($row_evento['data_final']);
  $elemento = '';
  $elemento .= '<div class="evento">';
  if ($row_evento['link'] != ''){
    $elemento .= '<a href="' . $row_evento['link'] . '">';
  }
  $elemento .= '<span class="evento-titulo">' . $row_evento['titulo'] . '</span>';
  if ($row_evento['link'] != ''){
    $elemento .= '</a>';
  }
  $elemento .= '<span> - </span>';
  $elemento .= show_intervalo_data($data_inicial, $data_final);
  $elemento .= '<span class="evento-local">' . $row_evento['local'] . '</span>';
  $elemento .= show_action_icon('Editar', 'update.png', 'eventos_update.php', $row_evento['id'], $login_adm);
  $elemento .= show_action_icon('Apagar', 'delete.png', 'eventos_delete.php', $row_evento['id'], $login_adm);
  $elemento .= '</div>';
  return $elemento;
}

function show_intervalo_data($data_inicial, $data_final) {
  $elemento = '<span class="evento-data">';
  if ($data_inicial == $data_final) {
    $elemento .= utf8_encode(strftime('%d de %B de %Y', $data_inicial));
  } else if ( strftime('%m', $data_inicial) == strftime('%m', $data_final) ) {
    $elemento .= strftime('%d', $data_inicial) . '-' . strftime('%d ', $data_final);
    $elemento .= utf8_encode(strftime('de %B de %Y', $data_final));
  } else {
    $elemento .= utf8_encode(strftime('%d de %B ', $data_inicial));
    $elemento .= 'até ';
    $elemento .= utf8_encode(strftime('%d de %B de %Y', $data_final));
  }
  $elemento .= ',</span>';
  return $elemento;
}

function show_intervalo_data_calendario($data_inicial, $data_final) {
  $elemento = '';
  if ($data_inicial == $data_final) {
    $elemento .= strftime('%d', $data_inicial);
  } else if ( strftime('%m', $data_inicial) == strftime('%m', $data_final) ) {
    $elemento .= strftime('%d', $data_inicial) . '-' . strftime('%d', $data_final);
  } else {
    $elemento .= strftime('%d-', $data_inicial);
    $elemento .= strftime('%d/%m', $data_final);
  }
  return $elemento;
}

function show_action_icon($action, $action_img, $action_file, $id, $login_adm) {
  $action_img_url = get_bloginfo('template_url') . '/imagens//' . $action_img;
  $action_file_url = get_bloginfo('template_url') . '/' . $action_file;
  $elemento = '<span class="action_icon eventos_icon">';
  $elemento .= '<a class="icon ' . $login_adm . '" href="' . $action_file_url . '?id=' . $id . '&last_page='. get_page_uri() .'" title="' . $action . '">';
  $elemento .= '<img src="' . $action_img_url . '" alt="' . $action . 'icon"/>';
  $elemento .= '</a>';
  $elemento .= '</span>';
  return $elemento;
}
