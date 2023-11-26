<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Definindo as rotas da API
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Rota para obter todos os posts
    if (isset($_GET['action']) && $_GET['action'] === 'get_posts') {
        $sql = "SELECT * FROM posts ORDER BY id DESC";
        $result = $conn->query($sql);

        $posts = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $post = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'content' => $row['content']
                );
                $posts[] = $post;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($posts);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rota para criar um novo post
    if (isset($_POST['action']) && $_POST['action'] === 'create_post') {
        $title = $_POST['title'];
        $content = $_POST['content'];

        $sql = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";
        if ($conn->query($sql) === true) {
            http_response_code(201);
        } else {
            http_response_code(500);
            echo "Erro ao criar o post: " . $conn->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Rota para excluir um post
    parse_str(file_get_contents("php://input"), $deleteData);
    $postId = $deleteData['id'];

    $sql = "DELETE FROM posts WHERE id = '$postId'";
    if ($conn->query($sql) === true) {
        http_response_code(200);
    } else {
        http_response_code(500);
        echo "Erro ao excluir o post: " . $conn->error;
    }
}

$conn->close();
?>