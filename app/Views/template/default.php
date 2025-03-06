<?= $this->include('template/default/header') ?>
<?= $this->include('template/default/sidebar') ?>
<?= $this->include('template/default/start_content') ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <?= $this->renderSection('content') ?>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?= $this->include('template/default/end_content') ?>
<?= $this->include('template/default/control_sidebar') ?>
<?= $this->include('template/default/footer') ?>