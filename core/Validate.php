<?php 

class Validate
{
    protected $rules;
    protected $pdo;

    public function __construct($pdo)
    {
        $_SESSION['errorMessageBag'] = [];
        $_SESSION['oldInputValues'] = [];

        $this->pdo = $pdo;
    }

    public function field($rules = [])
    {
        $this->rules = $rules;

        $this->validation();
    }

    protected function validation()
    {
        $previous_field = '';

        foreach ($this->rules as $field => $value) {
            foreach ($value as $key => $rule) {
                // if there is rule with params
                if (strpos($rule, ':')) {
                    $str_part = explode(':', $rule);
                    $name = $str_part[0];
                    $params = $str_part[1];

                    if ($name == 'min' && strlen($_POST[$field]) < $params) 
                    {
                        $_SESSION['errorMessageBag'][$field] = "The $field must be at least $params character(s).";
                        break;
                    }

                    if ($name == 'max' && strlen($_POST[$field]) > $params) 
                    {
                        $_SESSION['errorMessageBag'][$field] = "The $field must not be greater than $params character(s).";
                        break;
                    }

                    if ($name == 'email') 
                    {
                        $pattern = '/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/';

                        // if email address is invalid format.
                        if (! preg_match($pattern, $_POST[$field])) {
                            $_SESSION['errorMessageBag'][$field] = "Please enter valid email address.";
                            break;
                        } else {
                            // vaild but un-acceptable Tld
                            $accept = explode(',', $params);

                            $request_tld = substr($_POST[$field], strpos($_POST[$field], '.') - strlen($_POST[$field]) + 1);

                            if(! in_array($request_tld, $accept)) {
                                $_SESSION['errorMessageBag'][$field] = "The $field field accepted only " . '.' .implode(', .', $accept) . ' TLD name.';
                                break;
                            } 
                        }
                    }

                    if ($name == 'unique')
                    {
                        $options = explode(',', $params);
                        $table = $options[0];
                        $column = $options[1];
                        $ignoreID = $options[2] ?? '';

                        if ($ignoreID) {
                            $stmt = $this->pdo->prepare("
                                SELECT * FROM $table WHERE $column = ? AND `id` != ?
                            ");
                            $stmt->execute([ $_POST[$field], $ignoreID ]);

                            if ($stmt->rowCount()) {
                                $_SESSION['errorMessageBag'][$field] = "The $field is already exists.";
                                break;
                            }
                        } else {
                            $stmt = $this->pdo->prepare("
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
                        $options = explode(',', $params);
                        $table = $options[0];
                        $column = $options[1];

                        if ($column == 'password') {
                            $stmt = $this->pdo->prepare("
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

                            $stmt = $this->pdo->prepare("
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
                        $options = explode(',', $params);
                        $table = $options[0];
                        $column = $options[1];
                        $ignoreID = $options[2] ?? '';

                        $stmt = $this->pdo->prepare("
                            SELECT $column FROM $table WHERE `id` = ?
                        ");
                        $stmt->execute([$ignoreID]);

                        if ($stmt->fetchColumn()) {
                            break;
                        }
                    }
                } else {
                    if ($rule == 'required' && empty($_POST[$field])) 
                    {
                        $_SESSION['errorMessageBag'][$field] = "The $field field is required.";
                        break;
                    }

                    if ($rule == 'image' && !empty($_FILES[$field]['type']))
                    {
                        $uploaded_ext = str_replace('image/', '', $_FILES[$field]['type']);
                        $ext = ['jpeg', 'jpg', 'png'];

                        if (! in_array($uploaded_ext, $ext) ) {
                            $_SESSION['errorMessageBag'][$field] = "The $field field accepted only " . implode(', ', $ext) . '.';
                            break;
                        }
                    }

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
                            $accept = ['com', 'net', 'com.mm', 'edu'];

                            $request_tld = substr($_POST[$field], strpos($_POST[$field], '.') - strlen($_POST[$field]) + 1);

                            if(! in_array($request_tld, $accept)) {
                                $_SESSION['errorMessageBag'][$field] = "The $field field accepted only " . '.' .implode(', .', $accept) . ' TLD name.';
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
    }
}

$validate = new Validate($pdo);