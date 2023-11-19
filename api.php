<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco_de_dados";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Definindo as rotas da API
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Rota para obter todos os posts
    if ($_GET['action'] === 'get_posts') {
        $sql = "SELECT * FROM posts";
        $result = $conn->query($sql);
        $posts = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($posts);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rota para criar um novo post
    if ($_POST['action'] === 'create_post') {
        $title = $_POST['title'];
        $content = $_POST['content'];

        $sql = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";
        if ($conn->query($sql) === TRUE) {
            echo "Post criado com sucesso!";
        } else {
            echo "Erro ao criar o post: " . $conn->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Rota para atualizar um post existente
    if ($_GET['action'] === 'update_post') {
        $post_id = $_GET['post_id'];
        $title = $_PUT['title'];
        $content = $_PUT['content'];

        $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$post_id";
        if ($conn->query($sql) === TRUE) {
            echo "Post atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar o post: " . $conn->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Rota para excluir um post
    if ($_GET['action'] === 'delete_post') {
        $post_id = $_GET['post_id'];

        $sql = "DELETE FROM posts WHERE id=$post_id";
        if ($conn->query($sql) === TRUE) {
            echo "Post excluído com sucesso!";
        } else {
            echo "Erro ao excluir o post: " . $conn->error;
        }
    }
}

$conn->close();
?>
