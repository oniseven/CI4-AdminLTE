<?php
  namespace App\Libraries;

  use App\Types\TemplateInterface;
  use App\Models\MenuModel;

  class Template implements TemplateInterface {
    protected string $title = 'Default Title';
    protected array $css = [];
    protected array $js = [];
    protected array $plugins_css = [];
    protected array $plugins_js = [];
    protected bool $show_content_toolbar = true; 
    protected bool $show_breadcrums = true; 
    protected bool $show_footer = true; 
    protected array $classes = ["body" => ""];
    protected bool $sidebar_collapse = false;

    private array $allowed_tags = ["body"];
    private array $list_elements = ["content-toolbar", "breadcrums", "footer"];

    /**
     * Function to add page title, default: Default Title
     * 
     * @param string $title
     * 
     * @return object
     */
    public function page_title(string $title): object {
      $this->title = $title;
      return $this;
    }

    /**
     * Function to add page css
     * it could be 3rd Parties plugin css or current page custom css it self
     * 
     * @param string|list<string> $paths
     * 
     * @return object
     */
    public function page_css(string|array $paths): object {
      if(!empty($paths)){
        $this->css = is_array($paths) ? $paths : [$paths];
      }

      return $this;
    }

    /**
     * Function to add page js
     * it could be 3rd Parties plugin js or current page custom js it self
     * 
     * @param string|list<string> $paths
     * 
     * @return object
     */
    public function page_js(string|array $paths): object {
      if(!empty($paths)){
        $this->js = is_array($paths) ? $paths : [$paths];
      }

      return $this;
    }

    /**
     * Function to add some plugin css and js in header and footer
     * list plugins can be set on folder app/Config/Plugins.php
     * 
     * @param string|list<string> $vendor
     * 
     * @return object
     */
    public function plugins(string|array $vendors): object {
      $plugins = config('Plugins');
      $vendors = !is_array($vendors) ? [$vendors] : $vendors;
      foreach ($vendors as $key => $vendor) {
        if(isset($plugins->$vendor)){
          $plugin = $plugins->$vendor;
          if($plugin['css']) $this->plugins_css = array_merge($this->plugins_css, $plugin['css']);
          if($plugin['js']) $this->plugins_js = array_merge($this->plugins_js, $plugin['js']);
        }
      }

      return $this;
    }

    /**
     * Function to hide content toolbar div
     * Use it only when you use default template
     * not a print tempate
     * 
     * @return object
     */
    public function hide_content_toolbar(): object {
      $this->show_content_toolbar = false;
      return $this;
    }

    /**
     * Function to hide breadcrums
     * Use it only when you use default template
     * not a print tempate
     * 
     * @return object
     */
    public function hide_breadcrums(): object {
      $this->show_breadcrums = false;
      return $this;
    }


    /**
     * Function to hide footer div / section
     * 
     * @return object
     */
    public function hide_footer(): object {
      $this->show_footer = false;
      return $this;
    }

    /**
     * Function to hide some section in template
     * such as content_title, breadcrums, and footer for now
     * you can add more if you want to
     * Use it only when you use default template
     * not a print tempate
     * 
     * @param string|list<string> $indexs
     * 
     * @return object
     */
    public function hide(string|array $elements): object{
      if(!is_array($elements)){
        if(!in_array($elements, $this->list_elements)){
          throw new \Exception("Element \"{$elements}\" its not on the allowed list of element to hide.");
        }

        $this->{"show_{$elements}"} = false;
      } else {
        foreach ($elements as $key => $element) {
          if(!in_array($element, $this->list_elements)){
            throw new \Exception("Element \"{$element}\" its not on the allowed list of element to hide.");
          }

          $this->{"show_{$element}"} = false;
        }
      }

      return $this;
    }

    /**
     * Function to add costum class to a tag that exist in allowed_tags variable
     * 
     * @param string $classes
     * 
     * @return object
     */
    public function tag_class($tag, $classes): object {
      if(!in_array($tag, $this->allowed_tags)){
        throw new \Exception("Tag \"{$tag}\" its not on the allowed list tags for adding class.");
      }

      $this->classes[$tag] = $classes;
      return $this;
    }

    /**
     * Function to minimize the sidebar menu
     * 
     * @return object
     */
    public function collapse_sidebar(): object {
      $this->sidebar_collapse = true;
      return $this;
    }

    /**
     * Function to render html view
     * 
     * @param string  $view         page view file
     * @param array   $data         template data and settings
     * 
     * @return html
     */
    public function render(string $view, $data = []): string {
      $data['show'] = [
        "content-toolbar" => $this->show_content_toolbar,
        "breadcrums" => $this->show_breadcrums,
        "footer" => $this->show_footer
      ];
      $data['page_title'] = $this->title;
      $data['plugin_css'] = array_unique($this->plugins_css);
      $data['plugin_js'] = array_unique($this->plugins_js);
      $data['page_css'] = array_unique($this->css);
      $data['page_js'] = array_unique($this->js);
      $data['classes'] = $this->classes;
      $data['sidebar_collapse'] = $this->sidebar_collapse ? "sidebar-collapse" : "";
      $data['menu'] = $this->get_menu();

      return view($view, $data);
    }

    /**
     * Function to get menu data
     * 
     * @return array
     * 
     */
    private function get_menu() {
      $menuModel = new MenuModel();

      $menus = $menuModel->getActive();

      if(!$menus || empty($menus)) {
        return [
          "top" => [],
          "left" => []
        ];
      }

      $topMenus = array_filter($menus, function($row) {
        if($row->position === 'top') {
          return true;
        }

        return false;
      });

      $leftMenus = array_filter($menus, function($row) {
        if($row->position === 'left') {
          return true;
        }

        return false;
      });

      return [
        "top" => $topMenus ?? [],
        "left" => $leftMenus ?? []
      ];
    }
  }