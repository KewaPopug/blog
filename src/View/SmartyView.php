<?php
declare(strict_types=1);

namespace App\View;

use Smarty;

final class SmartyView
{
    private Smarty $smarty;

    public function __construct(string $templateDirectory, string $basePath)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($templateDirectory);
        $this->smarty->setCompileDir(sys_get_temp_dir() . '/blog-smarty-compile');
        $this->smarty->setCacheDir(sys_get_temp_dir() . '/blog-smarty-cache');
        $this->smarty->escape_html = true;
        $this->smarty->assign([
            'basePath' => $basePath,
            'currentYear' => date('Y'),
        ]);
    }

    public function render(string $template, array $data = []): void
    {
        $this->smarty->assign($data);
        $this->smarty->display($template);
    }
}
