<?php
declare(strict_types=1);

namespace projet5\View ;

class View
{
    protected $viewPath = '../templates/';

    public function render(string $view, string $template, array $variables = []):void// méthode pour afficher le rendu des views
    {
        ob_start();
        extract($variables);
        require($this->viewPath . $view . '.html.php');
        $content = ob_get_clean();
        require($this->viewPath . $template . '.html.php');
    }
}
