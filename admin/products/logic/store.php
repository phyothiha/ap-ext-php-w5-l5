<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset( $_POST['id'] ) ? $_POST['id'] : '';

    if (isset($_POST['_method']) && strtolower($_POST['_method']) == 'delete') {
        $stmt = $pdo->prepare("
            DELETE FROM `posts` WHERE `id` = ?
        ");
        $result = $stmt->execute([$id]);

        if ($result) {
            echo "<script>alert('Successfully Deleted'); window.location.href='/admin/posts/index.php';</script>";
        } else {
            echo "<script>alert('Error'); window.location.href='/admin/posts/index.php';</script>";
        }
    } else {

        $validate->field([
            'title' => ['required', 'min:2', 'max:255'],
            'content' => ['required', 'min:5', 'max:255'],
            'featured_image' => ['nullable', 'image'],
        ]);

        if (empty($_SESSION['errorMessageBag'])) {
             $title = $_POST['title'];
             $content = $_POST['content'];
             $author_id = $_SESSION['user_id'];
             $image = $_FILES['featured_image']['name'];

             if ($_FILES['featured_image']['name']) {
                 $file =  '../../images/' . $image;
                 $image_type = pathinfo($file, PATHINFO_EXTENSION);

                 move_uploaded_file($_FILES['featured_image']['tmp_name'], $file);
            } 

            if (isset($_POST['_method']) && strtolower($_POST['_method']) == 'put') {
                $stmt = $pdo->prepare("
                    UPDATE `posts` SET `title` = ?, `content` = ?, `image` = ?, `author_id` = ? WHERE `id` = ?
                ");
                $result = $stmt->execute([$title, $content, $image, $author_id, $id]);

                if ($result) {
                    echo "<script>alert('Successfully Updated'); window.location.href='/admin/posts/index.php';</script>";
                } else {
                    echo "<script>alert('Error'); window.location.href='/admin/posts/index.php';</script>";
                }
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `posts`(`title`, `content`, `image`, `author_id`)
                    VALUES (?, ?, ?, ?)
                ");
                $result = $stmt->execute([$title, $content, $image, $author_id]);

                if ($result) {
                    echo "<script>alert('Successfully Created'); window.location.href='/admin/posts/index.php';</script>";
                } else {
                    echo "<script>alert('Error'); window.location.href='/admin/posts/index.php';</script>";
                }
            }
        }
    }
} 