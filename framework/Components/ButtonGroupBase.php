<?php

namespace Atusan\Components;

use Atusan\Components\ComponentBase;

abstract class ButtonGroupBase extends ComponentBase
{
  /**
   * 
   */
  protected function postConstruct() {}

  protected function getCommonProperties($xml)
  {
    $name = $xml->getAttribute('name');
    $text = $xml->getAttribute('text');
    $icon = ($xml->hasAttribute('icon')) ? "<i class=\"{$xml->getAttribute('icon')}\"></i>" : '';
    $click = ($xml->hasAttribute('type', 'html', true)) ? '' : " onClick=\"{$name}(event)\"";

    return [
      'name' => $name,
      'text' => $text,
      'icon' => $icon,
      'click' => $click
    ];
  }
}
