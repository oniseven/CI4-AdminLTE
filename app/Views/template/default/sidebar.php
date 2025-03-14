  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- populate the website menu -->
      <?php
        $leftMenu = $menu["left"];
        $rootMenu = array_filter($leftMenu, function($row){
          if(empty($row->parent_id)) {
            return true;
          }

          return false;
        });

        if(!function_exists('generateChild')) {
          function generateChild($parent_id, $menus) {
            $childs = array_filter($menus, function($row) use ($parent_id) {
              if($row->parent_id === $parent_id) {
                return true;
              }

              return false;
            });

            $html = '<ul class="nav nav-treeview">';
            foreach ($childs as $key => $child) {
              $html .= '<li class="nav-item">';
              $html .= "<a href='{$child->link}' class='nav-link'>";
              $html .= "<i class='nav-icon {$child->icon}'></i>";
              $html .= "<p>{$child->name}";
              $html .= !$child->is_last ? '<i class="right fas fa-angle-left"></i>' : "";
              $html .= "</p>";
              $html .= '</a>';
              $html .= (!$child->is_last) ? generateChild($child->id, $menus) : '';
              $html .= '</li>';
            }
            $html .= '</ul>';

            return $html;
          }
        }
      ?>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php
          foreach ($rootMenu as $key => $row) {
        ?>
          <li class="nav-item">
            <a href="<?=$row->link?>" class="nav-link">
              <i class="nav-icon <?=$row->icon?>"></i>
              <p>
                <?=$row->name?>
                <?php if(!$row->is_last) { ?>
                  <i class="right fas fa-angle-left"></i>
                <?php } ?>
              </p>
            </a>
            <?=(!$row->is_last ? generateChild($row->id, $leftMenu) : "")?>
          </li>
        <?php
          }
        ?>
        </ul>
      </nav>
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">