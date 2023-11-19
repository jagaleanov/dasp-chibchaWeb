<?php

namespace src\services;

class ValidationService
{
    private static $instance = null;
    private $errors;
    private $sanitizedData;

    private function __construct()
    {
        $this->errors = [];
        $this->sanitizedData = [];
    }

    // Método estático para obtener la única instancia 
    public static function getInstance()
    {
        // Si la instancia no existe, la crea
        if (self::$instance == null) {
            self::$instance = new self();
        }
        // Devuelve la única instancia de DatabaseService
        return self::$instance;
    }

    private function sanitize($data, $rules)
    {
        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? '';
            foreach ($ruleSet as $rule) {
                switch ($rule) {
                    case 'trim':
                        $value = trim($value);
                        break;
                    case 'strip_tags':
                        $value = strip_tags($value);
                        break;
                    case 'escape':
                        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                        break;
                    // Agregar aquí más reglas de saneamiento según sea necesario
                }
            }
            $this->sanitizedData[$field] = $value;
        }
    }

    public function validate($data, $arrayOfRules)
    {
        $this->sanitize($data, array_column($arrayOfRules, 'rules', 'field'));

        foreach ($arrayOfRules as $ruleSet) {
            $field = $ruleSet['field'];
            $label = $ruleSet['label'] ?? $field; // Usa 'field' como respaldo si 'label' no está definido
            $value = $this->sanitizedData[$field];

            foreach ($ruleSet['rules'] as $rule) {
                list($ruleName, $param) = explode(':', $rule) + [null, null];
                switch ($ruleName) {
                    case 'required':
                        if (empty($value)) {
                            $this->errors[] = "El campo $label es requerido.";
                        }
                        break;

                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->errors[] = "El campo $label debe ser un email válido.";
                        }
                        break;

                    case 'max_length':
                        if (strlen($value) > $param) {
                            $this->errors[] = "El campo $label no debe superar los $param caracteres.";
                        }
                        break;

                    case 'min_length':
                        if (strlen($value) < $param) {
                            $this->errors[] = "El campo $label debe tener al menos $param caracteres.";
                        }
                        break;

                    case 'number':
                        if (!is_numeric($value)) {
                            $this->errors[] = "El campo $label debe ser un número.";
                        }
                        break;

                    case 'in_list':
                        $options = explode(',', $param);
                        if (!in_array($value, $options)) {
                            $this->errors[] = "El value del campo $label no es válido.";
                        }
                        break;
                    case 'integer':
                        if (preg_match('/^-?\d+$/', $value) && !filter_var((int)$value, FILTER_VALIDATE_INT)) {
                            $this->errors[] = "El campo $label debe ser un número entero.";
                        }
                        break;

                    case 'min':
                        if (is_numeric($value) && $value < $param) {
                            $this->errors[] = "El valor de $label debe ser al menos $param.";
                        }
                        break;

                    case 'max':
                        if (is_numeric($value) && $value > $param) {
                            $this->errors[] = "El valor de $label no debe superar $param.";
                        }
                        break;
                    case 'alpha':
                        if (!ctype_alpha(str_replace(' ', '', $value))) {
                            $this->errors[] = "El campo $label debe contener solo caracteres alfabéticos.";
                        }
                        break;

                    case 'url':
                        if (!filter_var($value, FILTER_VALIDATE_URL)) {
                            $this->errors[] = "El campo $label debe ser una URL válida.";
                        }
                        break;
                }
            }
        }

        return (object)[
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'sanitizedData' => $this->sanitizedData
        ];
    }
}
