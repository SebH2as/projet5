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
            function ($text) {
                $chars = 1200;
                if (mb_strlen($text) <= $chars) {
                    return html_entity_decode(strip_tags(htmlspecialchars_decode($text)));
                }
                $text = $text." ";
                $text = mb_substr($text, 0, $chars);
                $text = mb_substr($text, 0, mb_strrpos($text, ' '));
                $text = $text."...";
                
                return html_entity_decode(strip_tags(htmlspecialchars_decode($text))).' (Lire la suite)';
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
