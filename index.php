<?php

// Gestion des erreurs //
const ERROR_REQUIRED = 'Veuillez renseigner une tâche Todo';
const ERROR_LENGTH = 'Veuillez entrer au moins 5 caractères';

$error = '';
$filename = __DIR__ . "/data/todos.json";
$todos = [];
$todo = '';

if (file_exists($filename)) {
  $data = file_get_contents($filename);
  $todos = json_decode($data, true) ?? [];
}

// Vérification de la méthode POST et assainissement des champs //
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $todo = $_POST['todo'] ?? '';

  // On vérifie les tâches Todo et on les attribue aux erreurs si nécessaire //
  if (!$todo) {
    $error = ERROR_REQUIRED;
  } elseif (mb_strlen($todo) < 5) {
    $error = ERROR_LENGTH;
  }

  // S'il n'y a pas d'erreur, on assigne les valeurs au tableau qui contient les tâches Todo //
  if (!$error) {
    $todos = [...$todos, [
      'name' => $todo,
      'done' => false,
      'id' => time()
    ]];
    file_put_contents($filename, json_encode($todos));

    // Redirection vers l'index si l'ajout a fonctionné //
    header('Location: /');
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'includes/head.php' ?>
  <title>TodoP - Projet PHP</title>
</head>

<body>
  <div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
      <div class="todo-container">
        <h1>Todo List PHP</h1>
        <form action="/" method="POST" class="todo-form">
          <input value="<?= $todo ?>" name="todo" type="text">
          <button class="btn btn-primary">Ajouter</button>
        </form>
        <?php if ($error) : ?>
          <p class="text-danger">
            <?= $error ?>
          </p>
        <?php endif; ?>
        <ul class="todo-list">
          <?php foreach ($todos as $t) : ?>
            <li class="todo-item <?= $t['done'] ? 'low-opacity' : '' ?>">
              <span class="todo-name"><?= $t['name'] ?></span>
              <a href="/add.php?id=<?= $t['id'] ?>">
                <button class="btn btn-success btn-small"><?= $t['done'] ? 'Annuler' : 'Valider' ?></button>
              </a>
              <a href="/delete.php?id=<?= $t['id'] ?>">
                <button class="btn btn-danger btn-small">Supprimer</button>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
  </div>
</body>

</html>