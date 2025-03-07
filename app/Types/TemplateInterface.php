<?php

namespace App\Types;

interface TemplateInterface {
  /**
   * Page_title method that accept string.
   * 
   * @param string $title The title of the page
   */
  public function page_title(string $title): object;

  /**
   * Page_css method that accept string or an array of string.
   * 
   * @param string|list<string> $paths path to the page css
   */
  public function page_css(string|array $paths): object;

  /**
   * Page_js method that accept string or an array of string.
   * 
   * @param string|list<string> $paths path to the page js
   */
  public function page_js(string|array $paths): object;

  /**
    * Plugins method that accepts a string or an array of strings.
    *
    * @param string|list<string> $plugins The plugin name or array of plugin names.
    */
  public function plugins(string|array $plugins): object;

  /**
   * Methode to hide content toolbar
   */
  public function hide_content_toolbar(): object;

  /**
   * Methode to hide brreaddcrums
   */
  public function hide_breadcrums(): object;

  /**
   * Methode to hide footer
   */
  public function hide_footer(): object;

  /**
    * Hide method that accepts an ENUM or an array of ENUMs.
    *
    * @param string|list<string> $elements List element that wanted to be hide
    */
  public function hide(string|array $elements): object;

  /**
    * Method to add custom class on specific list of class in allowed_tags property
    *
    * @param string $tags tag which gonna get more class
    * @param string $classes custom class of the seleccted class
    */
  public function tag_class(string $tag, string $classes): object;

  /**
   * Method to hide sidebar
   */
  public function collapse_sidebar(): object;

  /**
   * Method to render the template
   * 
   * @param string $view
   * @param array $data
   */
  public function render(string $view, array $data): string;
}