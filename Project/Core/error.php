<?php

namespace Core;

class Error {
    private static $instance = null;
    private $errors = [];
    
    public function __construct() {}

    public function addError($message) {
        $this->errors[] = [
            'message' => $message,
            'timestamp' => time()
        ];
    }

    public function getErrors() {
        return $this->errors;
    }

    public function clearErrors() {
        $this->errors = [];
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getErrorsByType($type) {
        return array_filter($this->errors, function($error) use ($type) {
            return $error['type'] === $type;
        });
    }

    public function display() {
        if (empty($this->errors)) {
            return '';
        }

        $html = '<div class="error-container">';
        foreach ($this->errors as $error) {
            $html .= 
                '<div class="error">
                    <p class="alert__message">'.htmlspecialchars($error['message']).'</p>
                </div>'
            ;
        }
        $html .= '</div>';

        $this->clearErrors();

        return $html;
    }

}
