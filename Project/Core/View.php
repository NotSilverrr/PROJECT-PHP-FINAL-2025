<?php 
namespace Core;

class View {
    protected $data = [];
    protected $layout = 'main';
    protected $view;

    public static function make($view, $data = []) {
        $instance = new self();
        $instance->view = $view;
        $instance->data = $data;

        return $instance;
    }

    public function with($key, $value = null) {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    public function layout($layout) {
        $this->layout = $layout;
        return $this;
    }

    public function render() {
        extract($this->data);

        $viewPath = $this->parseViewPath($this->view);

        $layoutPath = $this->parseLayoutPath($this->layout);

        ob_start();
        require __DIR__ . $viewPath;
        $content = ob_get_clean();

        ob_start();
        require __DIR__ . $layoutPath;
        return ob_get_clean();
    }

    protected function parseViewPath($view) {
        $path = str_replace('.', '/', $view);
        return "/../App/Views/{$path}.php";
    }

    protected function parseLayoutPath($layout) {
        return "/../App/Views/layouts/{$layout}.php";
    }

    public function __toString() {
        return $this->render();
    }
}
