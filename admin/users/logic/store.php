<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $redirect_to = '/admin/users';

    $id = isset( $_POST['id'] ) ? $_POST['id'] : '';

    // DESTROY
    if (
        isset($_POST['_method']) && 
        strtolower($_POST['_method']) == 'delete' &&
        ! empty($id)
    ) {
        $stmt = $pdo->prepare("
            DELETE FROM 
                `users` 
            WHERE 
                `id` = ?
        ");

        $result = $stmt->execute([$id]);

        if (! $result) {
            echo "<script>alert('Error while executing the query.'); window.location.href='$redirect_to';</script>";   
        }

        echo "<script>alert('Item has been successfully deleted.'); window.location.href='$redirect_to';</script>";
    }

    $validatedData = Validate::field([
        'name' => ['required', 'min:5', 'max:20', 'unique:users,name,' . $id],
        'email' => ['required', 'email:com,net', 'unique:users,email,' . $id],
        'password' => ['ignore:users,password,' . $id, 'required', 'min:8'], // ignore on update
        'role' => ['nullable']
    ]); 

    if (! empty($validatedData)) {
        extract($validatedData);

        $role = empty($role) ? 0 : 1;

        // UPDATE
        if (
            isset($_POST['_method']) && 
            ( strtolower($_POST['_method']) == 'put' ||
            strtolower($_POST['_method']) == 'patch' ) &&
            ! empty($id)
        ) {
            
            if ($password) {
                $password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    UPDATE 
                        `users` 
                    SET 
                        `name` = ?, 
                        `email` = ?, 
                        `password` = ?, 
                        `role` = ? 
                    WHERE 
                        `id` = ?
                ");

                $result = $stmt->execute([$name, $email, $password, $role, $id]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE 
                        `users` 
                    SET 
                        `name` = ?, 
                        `email` = ?, 
                        `role` = ? 
                    WHERE 
                        `id` = ?
                ");

                $result = $stmt->execute([$name, $email, $role, $id]);
            }

            if (! $result) {
                echo "<script>alert('Error while executing the query.'); window.location.href='$redirect_to';</script>";   
            }

            echo "<script>alert('Item has been successfully updated.'); window.location.href='$redirect_to';</script>";
        } else {

            // STORE
            $password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO 
                    `users` (`name`, `email`, `password`, `role`)
                VALUES 
                    (?, ?, ?, ?)
            ");

            $result = $stmt->execute([$name, $email, $password, $role]);

            if (! $result) {
                echo "<script>alert('Error while executing the query.'); window.location.href='$redirect_to';</script>";   
            }

            echo "<script>alert('Item has been successfully created.'); window.location.href='$redirect_to';</script>";
        }
    }
} 