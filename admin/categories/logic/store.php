<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset( $_POST['id'] ) ? $_POST['id'] : '';

    if (isset($_POST['_method']) && strtolower($_POST['_method']) == 'delete') {
        
        $stmt = $pdo->prepare("
            DELETE FROM `categories` WHERE `id` = ?
        ");
        $result = $stmt->execute([$id]);

        if ($result) {
            echo "<script>alert('Successfully Deleted'); window.location.href='/admin/categories/index.php';</script>";
        } else {
            echo "<script>alert('Error'); window.location.href='/admin/categories/index.php';</script>";
        }
    } else {
        $validate->field([
            'name' => ['required', 'min:2', 'max:255'],
            'description' => ['required', 'min:5', 'max:255'],
        ]);

        if (empty($_SESSION['errorMessageBag'])) {
             $name = $_POST['name'];
             $description = $_POST['description'];

            if (isset($_POST['_method']) && strtolower($_POST['_method']) == 'put') {
                $stmt = $pdo->prepare("
                    UPDATE `categories` SET `name` = ?, `description` = ? WHERE `id` = ?
                ");
                $result = $stmt->execute([$name, $description, $id]);

                if ($result) {
                    echo "<script>alert('Successfully Updated'); window.location.href='/admin/categories/index.php';</script>";
                } else {
                    echo "<script>alert('Error'); window.location.href='/admin/categories/index.php';</script>";
                }
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `categories`(`name`, `description`)
                    VALUES (?, ?)
                ");
                $result = $stmt->execute([$name, $description]);

                if ($result) {
                    echo "<script>alert('Successfully Created'); window.location.href='/admin/categories/index.php';</script>";
                } else {
                    echo "<script>alert('Error'); window.location.href='/admin/categories/index.php';</script>";
                }
            }
        }
    }
} 