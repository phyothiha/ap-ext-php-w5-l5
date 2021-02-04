<?php 

class Validate
{
    protected static $pdo;
    
    protected static $rules;

    protected static $validatedData = [];

    public function __construct($pdo)
    {
        $_SESSION['errorMessageBag'] = [];

        $_SESSION['oldInputValues'] = [];

        static::$pdo = $pdo;
    }

    public static function field($rules = [])
    {
        static::$rules = $rules;

        return static::validation();
    }

    protected static function validation()
    {
        $previous_field = '';

        foreach (static::$rules as $field => $value) {

            foreach ($value as $rule) {

                // Rule with argument
                if (strpos($rule, ':')) {

                    $rule = explode(':', $rule);
                    $name = $rule[0];
                    $args = $rule[1];

                    if (
                        $name == 'min' && 
                        strlen($_POST[$field]) < $args
                    ) {
                        $_SESSION['errorMessageBag'][$field] = "The $field must be at least $args character(s).";

                        break;
                    }

                    if (
                        $name == 'max' && 
                        strlen($_POST[$field]) > $args
                    ) {
                        $_SESSION['errorMessageBag'][$field] = "The $field must not be greater than $args character(s).";

                        break;
                    }

                    if (
                        $name == 'email'
                    ) {
                        $pattern = '/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/';

                        // if email address is invalid format.
                        if (
                            ! preg_match($pattern, $_POST[$field])
                        ) {
                            $_SESSION['errorMessageBag'][$field] = "Please enter valid email address.";

                            break;
                        } else {
                            // vaild but un-acceptable Tld
                            $acceptable_tld = explode(',', $args);

                            $tld = substr($_POST[$field], strpos($_POST[$field], '.') - strlen($_POST[$field]) + 1);

                            if (
                                ! in_array($tld, $acceptable_tld)
                            ) {
                                $_SESSION['errorMessageBag'][$field] = "The $field field accepted only " . '.' .implode(', .', $acceptable_tld) . ' TLD name.';

                                break;
                            } 
                        }
                    }

                    if (
                        $name == 'unique'
                    ) {
                        $options = explode(',', $args);
                        $table = $options[0];
                        $column = $options[1];
                        $ignoreID = $options[2] ?? '';

                        if (
                            $ignoreID
                        ) {
                            $stmt = static::$pdo->prepare("
                                SELECT * FROM $table WHERE $column = ? AND `id` != ?

                                SELECT EXISTS (
                                    SELECT * FROM $table WHERE $column = ? AND `id` = ?
                                ) as `exists`;
                            ");
                            $stmt->execute([$_POST[$field], $ignoreID]);

                            if ($stmt->rowCount()) {
                                $_SESSION['errorMessageBag'][$field] = "The $field is already exists.";

                                break;
                            }
                        } else {
                            $stmt = static::$pdo->prepare("
                                SELECT * FROM $table WHERE $column = ?
                            ");
                            $stmt->execute([ $_POST[$field] ]);

                            if ($stmt->rowCount()) {
                                $_SESSION['errorMessageBag'][$field] = "The $field is already exists.";

                                break;
                            }
                        }
                    }

                    if ($name == 'exists')
                    {
                        $options = explode(',', $args);
                        $table = $options[0];
                        $column = $options[1];

                        if ($column == 'password') {
                            $stmt = static::$pdo->prepare("
                                SELECT $column FROM $table WHERE `email` = ?
                            ");
                            // fixed logic * email column
                            $stmt->execute([$_POST['email']]);

                            if (! password_verify($_POST[$field], $stmt->fetchColumn())) {
                                $_SESSION['errorMessageBag'][$field] = "Wrong Credential.";
                                break;
                            }
                        } else {
                            $role = $options[2];
                            $type = ($options[3] == 'user') ? 0 : 1;

                            $stmt = static::$pdo->prepare("
                                SELECT * FROM $table WHERE $column = ? AND $role = ?
                            ");
                            $stmt->execute([ $_POST[$field], $type ]);

                            if (! $stmt->rowCount()) {
                                $_SESSION['errorMessageBag'][$field] = "The $field does not exists.";
                                break;
                            }
                        }
                    }

                    if ($name == 'ignore' && isset($_POST['_method']) && strtolower($_POST['_method']) == 'put' && empty($_POST[$field]))
                    {
                        $options = explode(',', $args);
                        $table = $options[0];
                        $column = $options[1];
                        $ignoreID = $options[2] ?? '';

                        $stmt = static::$pdo->prepare("
                            SELECT $column FROM $table WHERE `id` = ?
                        ");
                        $stmt->execute([$ignoreID]);

                        if ($stmt->fetchColumn()) {
                            break;
                        }
                    }
                } 
                // Rule without argument
                else {

                    // Required
                    if (
                        $rule == 'required' && 
                        empty($_POST[$field])
                    ) {
                        $_SESSION['errorMessageBag'][$field] = "The $field field is required.";

                        break;
                    }

                    // Image
                    if (
                        $rule == 'image' && 
                        ! empty($_FILES[$field]['type'])
                    ) {
                        $uploaded_ext = str_replace('image/', '', $_FILES[$field]['type']);
                        $ext = ['jpeg', 'jpg', 'png'];

                        if (
                            ! in_array($uploaded_ext, $ext)
                        ) {
                            $_SESSION['errorMessageBag'][$field] = "The $field field accepted only " . implode(', ', $ext) . '.';

                            break;
                        }
                    }

                    // Bail
                    if ($rule == 'bail')
                    {
                        // fixed logic * email column
                        if (isset($_SESSION['errorMessageBag'][$previous_field])) {
                            break;
                        }
                    }

                    if ($rule == 'email') 
                    {
                        $pattern = '/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/';

                        // if email address is invalid format.
                        if (! preg_match($pattern, $_POST[$field])) {
                            $_SESSION['errorMessageBag'][$field] = "Please enter valid email address.";
                            break;
                        } else {
                            // vaild but un-acceptable Tld
                            $acceptable_tld = ['com', 'net', 'com.mm', 'edu'];

                            $tld = substr($_POST[$field], strpos($_POST[$field], '.') - strlen($_POST[$field]) + 1);

                            if(! in_array($tld, $acceptable_tld)) {
                                $_SESSION['errorMessageBag'][$field] = "The $field field accepted only " . '.' .implode(', .', $acceptable_tld) . ' TLD name.';
                                break;
                            } 
                        }
                    }
                }
            }

            // can't figure it out how to handle $_FILE old input
            // fail if the needle contains multiple values
            if (! in_array('image', $value)) 
            {
                $_SESSION['oldInputValues'][$field] = $_POST[$field];
            }

            $previous_field = $field;
        }

        if (
            empty($_SESSION['errorMessageBag'])
        ) {
            $keys = array_keys(static::$rules);

            array_map(function ($value) {
                return static::$validatedData[$value] = $_POST[$value];
            }, $keys);

            return static::$validatedData;
        }
    }
}

$validate = new Validate($pdo);