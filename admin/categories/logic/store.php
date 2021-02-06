<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $redirect_to = '/admin/categories';
    
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    // DESTROY
    if (
        isset($_POST['_method']) && 
        strtolower($_POST['_method']) == 'delete' &&
        ! empty($id)
    ) {
        $stmt = $pdo->prepare("
            DELETE FROM 
                `categories` 
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
        'name' => ['required', 'min:2', 'max:255'],
        'description' => ['required', 'min:5', 'max:255'],
    ]);

    if (! empty($validatedData)) {
        extract($validatedData);

        // UPDATE
        if (
            isset($_POST['_method']) && 
            ( strtolower($_POST['_method']) == 'put' ||
            strtolower($_POST['_method']) == 'patch' ) &&
            ! empty($id)
        ) {

            $stmt = $pdo->prepare("
                UPDATE 
                    `categories` 
                SET 
                    `name` = ?, 
                    `description` = ? 
                WHERE 
                    `id` = ?
            ");

            $result = $stmt->execute([$name, $description, $id]);

            if (! $result) {
                echo "<script>alert('Error while executing the query.'); window.location.href='$redirect_to';</script>";   
            }

            echo "<script>alert('Item has been successfully updated.'); window.location.href='$redirect_to';</script>";

        } else {

            // STORE
            $stmt = $pdo->prepare("
                INSERT INTO 
                    `categories` (`name`, `description`)
                VALUES 
                    (?, ?)
            ");
            $result = $stmt->execute([$name, $description]);

            if (! $result) {
                echo "<script>alert('Error while executing the query.'); window.location.href='$redirect_to';</script>";   
            }

            echo "<script>alert('Item has been successfully created.'); window.location.href='$redirect_to';</script>";
        } 
    }   
} 