<?php

declare(strict_types=1);

namespace Projet5\View;

use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

final class View
{
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader('../templates');
        $this->twig = new Environment($loader, [
            'debug' => true]);

        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig->addExtension(new IntlExtension());
        $this->twig->addFunction(new \Twig\TwigFunction(
            'extrait',
            function ($value) {
                $espace = mb_strpos($value, ' ', 550);
                $extr = mb_substr($value, 0, $espace);
                return html_entity_decode(strip_tags(htmlspecialchars_decode($extr))).' (Lire la suite)';
            }
        ));
        $this->twig->addFunction(new \Twig\TwigFunction(
            'decode',
            function ($value) {
                if ($value !== null) {
                    return html_entity_decode((htmlspecialchars_decode($value)));
                }
            }
        ));
    }

    public function render(array $data): void
    {
        echo $this->twig->render("${data['template']}.html.twig", $data['data']);
    }
}
