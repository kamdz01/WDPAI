<?php
class ErrorController
{
    public function __construct()
    {
    }

    public function handle($error)
    {
        switch ($error) {
            case 'wrong_url':
                $this->wrongUrl();
                break;
            default:
                $this->defaultError();
                break;
        }
    }

    private function wrongUrl()
    {
        include_once 'src/views/notFound.php';
        exit();
    }

    private function defaultError()
    {
        echo "An error occurred!";
        exit();
    }
}
