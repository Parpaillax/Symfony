<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Censurator
{

  public function __construct(private readonly ContainerBagInterface $params)
  {

  }
  public function purify(?string $text): string
  {
    $filename = $this->params->get('app.censurator_file');
    if (file_exists($filename)) {
      $words = file($filename);
      foreach ($words as $unwantedWord) {
        $unwantedWord = str_replace(PHP_EOL, '', $unwantedWord);
        $replacement = str_repeat("*", mb_strlen($unwantedWord));
        $text = str_replace($unwantedWord, $replacement, $text);
      }
    }

    return $text;
  }
}