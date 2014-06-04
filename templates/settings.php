<?php if ($_POST['publisher_desk_hidden'] == 'yes'): ?>
  <?php $id = $_POST['publisher_desk_id']; ?>
  <?php update_option('publisher_desk_id', $id); ?>
  <?php $contextual = $_POST['publisher_desk_contextual']; ?>
  <?php update_option('publisher_desk_contextual', $contextual); ?>
  <div class="updated"><p><strong><?php _e('Settings updated.'); ?></strong></p></div>
<?php else: ?>
  <?php $id = get_option('publisher_desk_id'); ?>
  <?php $contextual = get_option('publisher_desk_contextual'); ?>
<?php endif; ?>
<div class="wrap">
  <?php echo '<h2>' . __('The Publisher Desk - Settings', 'publisher_desk_dom') . '</h2>'; ?>
  <form name="publisher_desk_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="publisher_desk_hidden" value="yes">
    <p><?php _e('Publisher ID: '); ?></p>
    <p><input type="text" name="publisher_desk_id" value="<?php echo $id; ?>" size="50"></p>
    <p>
      <?php if ($contextual == 1): ?>
        <input type="checkbox" name="publisher_desk_contextual" value="1" checked="checked">
      <?php else: ?>
        <input type="checkbox" name="publisher_desk_contextual" value="1">
      <?php endif; ?>
      <?php _e('Insert contextual unit after content'); ?>
    </p>
    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Update Settings', 'publisher_desk_dom' ) ?>" />
    </p>
  </form>
</div>