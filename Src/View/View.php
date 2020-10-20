<?php

declare(strict_types=1);

namespace Projet5\View;

use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Extra\Markdown;
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\Markdown\MarkdownRuntime;
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
        $this->twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
            public function load($class)
            {
                if (MarkdownRuntime::class === $class) {
                    return new MarkdownRuntime(new DefaultMarkdown());
                }
            }
        });
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig->addExtension(new MarkdownExtension());
        $this->twig->addExtension(new IntlExtension());
        $this->twig->addFunction(new \Twig\TwigFunction(
            'extr',
            function ($value) {
                $espace = mb_strpos($value, ' ', 1000);
                $extr = mb_substr($value, 0, $espace);
                return strip_tags(htmlspecialchars_decode($extr)).' (Lire la suite)';
            }
        ));
    }

    public function render(array $data): void
    {
        echo $this->twig->render("${data['template']}.html.twig", $data['data']);
    }
}
