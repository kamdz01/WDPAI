<?php
class AppController
{
    private static $instance = null;
    private $request;
    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
    }
    private static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new AppController();
        }
        return self::$instance;
    }
    public function isGet(): bool
    {
        return $this->request === 'GET';
    }
    public function isPost(): bool
    {
        return $this->request === 'POST';
    }

    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'src/views/' . $template . '.php';
        $errorPath = 'src/views/notFound.php';

        $output = 'File not found';

        if (file_exists($templatePath)) {
            extract($variables);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }
        else {
            ob_start();
            include $errorPath;
            $output = ob_get_clean();
        }
        print $output;
    }
}
