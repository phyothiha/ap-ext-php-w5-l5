<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $redirect_to = '/admin/products';

    $id = isset($_POST['id']) ? $_POST['id'] : '';

    // DESTROY
    if (
        isset($_POST['_method']) && 
        strtolower($_POST['_method']) == 'delete' &&
        ! empty($id)
    ) {
        $stmt = $pdo->prepare("
            DELETE FROM 
                `products` 
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
        'description' => ['nullable', 'min:5', 'max:255'],
        'category_id' => ['required', 'exists:categories,id'],
        'quantity' => ['required', 'numeric', 'digits_between:1,5'],
        'price' => ['required', 'numeric', 'digits_between:3,5'],
        'image' => ['nullable', 'image:png,jpg,jpeg'],
    ]);

    if (! empty($validatedData)) {
        extract($validatedData);

         if ($_FILES['image']['name']) {
            $image = $_FILES['image']['name'];
            $file =  '../../public/uploads/' . $image;
            $image_type = pathinfo($file, PATHINFO_EXTENSION);

            move_uploaded_file($_FILES['image']['tmp_name'], $file);
        }

        // UPDATE
        if (
            isset($_POST['_method']) && 
            ( strtolower($_POST['_method']) == 'put' ||
            strtolower($_POST['_method']) == 'patch' ) &&
            ! empty($id)
        ) {
            if (! $image) {
                $stmt = $pdo->prepare("
                    SELECT 
                        `image`
                    FROM 
                        `products`
                    WHERE
                        `id` = ?
                ");
                $stmt->execute([$id]);
                $image = $stmt->fetchColumn();
            }

            $stmt = $pdo->prepare("
                UPDATE 
                    `products` 
                SET 
                    `name` = ?, 
                    `description` = ? ,
                    `category_id` = ?,
                    `quantity` = ?,
                    `price` = ?,
                    `image` = ?
                WHERE 
                    `id` = ?
            ");

            $result = $stmt->execute([$name, $description, $category_id, $quantity, $price, $image, $id]);

            if (! $result) {
                echo "<script>alert('Error while executing the query.'); window.location.href='$redirect_to';</script>";   
            }

            echo "<script>alert('Item has been successfully updated.'); window.location.href='$redirect_to';</script>";

        } else {
            // STORE
            $stmt = $pdo->prepare("
                INSERT INTO 
                    `products` (`name`, `description`, `category_id`, `quantity`, `price`, `image`)
                VALUES 
                    (?, ?, ?, ?, ?, ?)
            ");
            $result = $stmt->execute([$name, $description, $category_id, $quantity, $price, $image]);

            if (! $result) {
                echo "<script>alert('Error while executing the query.'); window.location.href='$redirect_to';</script>";   
            }

            echo "<script>alert('Item has been successfully created.'); window.location.href='$redirect_to';</script>";
        } 
    }
} 