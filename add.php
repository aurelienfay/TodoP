<?php

// Chemin vers la Todo-List en JSON //
$filename = __DIR__ . "/data/todos.json";

// On protège et on assainit l'URL
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// On récupère l'ID de l'item à ajouter, puis on la met dans le fichier JSON
$id = $_GET['id'] ?? '';
if ($id) {
  $data = file_get_contents($filename);
  $todos = json_decode($data, true) ?? [];
  if (count($todos)) {
    $todoIndex = (int)array_search($id, array_column($todos, 'id'));
    $todos[$todoIndex]['done'] = !$todos[$todoIndex]['done'];
    file_put_contents($filename, json_encode($todos));
  }
}

// Redirection vers l'index //
header('Location: /');
