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

                // check the rule has arguments or not
                if (
                    strpos($rule, ':')
                ) {
                    $rule = explode(':', $rule);
                    list($rule, $arg) = $rule;
                }

                if (
                    $rule == 'min' && 
                    strlen($_POST[$field]) < $arg
                ) {
                    $_SESSION['errorMessageBag'][$field] = "The $field must be at least $arg character(s).";

                    break;
                }

                if (
                    $rule == 'max' && 
                    strlen($_POST[$field]) > $arg
                ) {
                    $_SESSION['errorMessageBag'][$field] = "The $field must not be greater than $arg character(s).";

                    break;
                }

                if (
                    $rule == 'email'
                ) {
                    $pattern = '/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/';

                    // if given email address is invalid.
                    if (
                        ! preg_match($pattern, $_POST[$field])
                    ) {
                        $_SESSION['errorMessageBag'][$field] = "Please enter valid email address.";

                        break;
                    } else {
                        $acceptable_tld = ['com', 'net', 'com.mm', 'edu'];

                        if ($arg) {
                            $acceptable_tld = explode(',', $arg);
                        }

                        // get last domain part [.]com can become [.]com[.]mm
                        $tld = substr($_POST[$field], strpos($_POST[$field], '.') - strlen($_POST[$field]) + 1);

                        if (
                            ! in_array($tld, $acceptable_tld)
                        ) {
                            $_SESSION['errorMessageBag'][$field] = "The $field field accepted only " . '.' .implode(', .', $acceptable_tld) . ' TLD.';

                            break;
                        } 
                    }
                }

                if (
                    $rule == 'unique'
                ) {

                    list($table, $column, $ignoreAttribute) = explode(',', $arg);

                    if (
                        ! empty($ignoreAttribute)
                    ) {
                        $stmt = static::$pdo->prepare("
                            SELECT 
                                 * 
                             FROM 
                                 $table 
                             WHERE
                                 $column = ? and `id` != ?
                        ");
                        $stmt->execute([$_POST[$field], $ignoreAttribute]);

                        if ($stmt->rowCount()) {
                            $_SESSION['errorMessageBag'][$field] = "The $field is already exists.";

                            break;
                        }
                    } else {
                        $stmt = static::$pdo->prepare("
                            SELECT 
                                * 
                            FROM 
                                $table 
                            WHERE
                                $column = ?
                        ");
                        $stmt->execute([$_POST[$field]]);

                        if ($stmt->rowCount()) {
                            $_SESSION['errorMessageBag'][$field] = "The $field is already exists.";

                            break;
                        }
                    }
                }

                if (
                    $rule == 'exists'
                ) {

                    list($table, $column) = explode(',', $arg);

                    if ($column == 'password') {
                        $stmt = static::$pdo->prepare("
                            SELECT 
                                $column
                            FROM 
                                $table 
                            WHERE 
                                $previous_field = ?
                        ");

                        $stmt->execute([$_POST[$previous_field]]);

                        if (! password_verify($_POST[$field], $stmt->fetchColumn())) {
                            $_SESSION['errorMessageBag'][$field] = "Wrong Credential.";

                            break;
                        }
                    }

                    if ($column == 'email') {
                        $stmt = static::$pdo->prepare("
                            SELECT 
                                * 
                            FROM 
                                $table 
                            WHERE 
                                $column = ?
                        ");
                        $stmt->execute([$_POST[$field]]);

                        if (! $stmt->rowCount()) {
                            $_SESSION['errorMessageBag'][$field] = "The $field has not registered it yet!";

                            break;
                        }
                    }
                }

                if (
                    $rule == 'ignore'
                ) {
                    list($table, $column, $ignoreAttribute) = explode(',', $arg);

                    if (
                        ! empty($ignoreAttribute)
                    ) {
                        $stmt = static::$pdo->prepare("
                            SELECT 
                                 * 
                             FROM 
                                 $table 
                             WHERE
                                 $column = ? and `id` != ?
                        ");
                        $stmt->execute([$_POST[$field], $ignoreAttribute]);

                        if ($stmt->rowCount()) {
                            break;
                        }
                    } 
                } 

                if (
                    $rule == 'nullable' &&
                    ( isset($_POST[$field]) && empty($_POST[$field]) ) || 
                    ( isset($_FILES[$field]) && empty($_FILES[$field]) )
                ) {
                    break;
                }

                if (
                    $rule == 'required' && 
                    empty($_POST[$field])
                ) {
                    $_SESSION['errorMessageBag'][$field] = "The $field field is required.";

                    break;
                }

                if (
                    $rule == 'numeric' &&
                    ! is_numeric($_POST[$field])
                ) {
                    $_SESSION['errorMessageBag'][$field] = "The $field must be a numeric value.";
                    
                    break;
                }

                if (
                    $rule == 'digits_between'
                ) {
                    list($min, $max) = explode(',', $arg);

                    if (
                        strlen($_POST[$field]) < $min
                    ) {
                        $_SESSION['errorMessageBag'][$field] = "The $field must not less than $min digits value.";
                    }

                    if (
                        strlen($_POST[$field]) > $max
                    ) {
                        $_SESSION['errorMessageBag'][$field] = "The $field must not greater than $max digits value.";
                    }

                    break;
                }

                if (
                    $rule == 'image' && 
                    ! empty($_FILES[$field]['type'])
                ) {
                    $uploaded_ext = str_replace('image/', '', $_FILES[$field]['type']);
                    
                    $ext = ['jpeg', 'jpg', 'png', 'gif', 'webp'];

                    if ($arg) {
                        $ext = explode(',', $arg);
                    }

                    if (
                        ! in_array($uploaded_ext, $ext)
                    ) {
                        $_SESSION['errorMessageBag'][$field] = "The $field field accepted only " . implode(', ', $ext) . '.';

                        break;
                    }
                }

                if (
                    $rule == 'bail' &&
                    isset($_SESSION['errorMessageBag'][$previous_field])
                ) {
                    break;
                }
            }

            // can't figure it out how to handle $_FILE old input
            if (
                $rule != 'image' && isset($_POST[$field])
            ) {
                $_SESSION['oldInputValues'][$field] = $_POST[$field];
            }

            $previous_field = $field;
        }

        if (
            empty($_SESSION['errorMessageBag'])
        ) {
            $keys = array_keys(static::$rules);

            array_map(function ($value) {
                return static::$validatedData[$value] = isset($_POST[$value]) 
                                                            ? $_POST[$value] 
                                                            : '';
            }, $keys);

            return static::$validatedData;
        }
    }
}

$validate = new Validate($pdo);