<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset( $_POST['id'] ) ? $_POST['id'] : '';

    if (isset($_POST['_method']) && strtolower($_POST['_method']) == 'delete') {
        $stmt = $pdo->prepare("
            DELETE FROM `users` WHERE `id` = ?
        ");
        $result = $stmt->execute([$id]);

        if ($result) {
            echo "<script>alert('Successfully Deleted'); window.location.href='/admin/users/index.php';</script>";
        } else {
            echo "<script>alert('Error'); window.location.href='/admin/users/index.php';</script>";
        }
    } else {
        $validate->field([
            'name' => ['required', 'min:5', 'max:20', 'unique:users,name,' . $id],
            'email' => ['required', 'email:com,net', 'unique:users,email,' . $id],
            'password' => ['ignore:users,password,' . $id, 'required', 'min:8'], // ignore on update
        ]);

        if (empty($_SESSION['errorMessageBag'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = empty($_POST['role']) ? 0 : 1;

            if (isset($_POST['_method']) && strtolower($_POST['_method']) == 'put') {

                if (empty($password)) {
                    $stmt = $pdo->prepare("
                        UPDATE `users` SET `name` = ?, `email` = ?, `role` = ? WHERE `id` = ?
                    ");

                    $result = $stmt->execute([$name, $email, $role, $id]);
                } else {
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("
                        UPDATE `users` SET `name` = ?, `email` = ?, `password` = ?, `role` = ? WHERE `id` = ?
                    ");
                    $result = $stmt->execute([$name, $email, $password, $role, $id]);
                }

                if ($result) {
                    echo "<script>alert('Successfully Updated'); window.location.href='/admin/users/index.php';</script>";
                } else {
                    echo "<script>alert('Error'); window.location.href='/admin/users/index.php';</script>";
                }
            } else {
                // encrypt password
                $password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO `users`(`name`, `email`, `password`, `role`)
                    VALUES (?, ?, ?, ?)
                ");
                $result = $stmt->execute([$name, $email, $password, $role]);

                if ($result) {
                    echo "<script>alert('Successfully Created'); window.location.href='/admin/users/index.php';</script>";
                } else {
                    echo "<script>alert('Error'); window.location.href='/admin/users/index.php';</script>";
                }
            }
        }
    }
} 