<?php

// Chemin vers la Todo-List en JSON //
$filename = __DIR__ . "/data/todos.json";

// On protège et on assainit l'URL
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// On récupère l'ID de l'item à supprimer, puis on l'onlève du fichier JSON
$id = $_GET['id'] ?? '';
if ($id) {
  $todos = json_decode(file_get_contents($filename), true);
  $todoIndex = array_search($id, array_column($todos, 'id'));
  array_splice($todos, $todoIndex, 1);
  file_put_contents($filename, json_encode($todos));
}

// Redirection vers l'index //
header('Location: /');
