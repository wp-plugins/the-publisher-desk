<?php if ( $_POST['publisher_desk_hidden'] == 'yes' ): ?>
  <?php $id = $_POST['publisher_desk_id']; ?>
  <?php update_option( 'publisher_desk_id', $id ); ?>
  <?php $contextual = $_POST['publisher_desk_contextual']; ?>
  <?php update_option( 'publisher_desk_contextual', $contextual ); ?>
  <?php $framebusters = $_POST['publisher_desk_framebusters']; ?>
  <?php update_option( 'publisher_desk_framebusters', $framebusters ); ?>
  <div class="updated"><p><strong><?php _e( 'Settings updated.' ); ?></strong></p></div>
<?php else: ?>
  <?php $id = get_option( 'publisher_desk_id' ); ?>
  <?php $contextual = get_option( 'publisher_desk_contextual' ); ?>
  <?php $framebusters = get_option( 'publisher_desk_framebusters' ); ?>
<?php endif; ?>
<div class="wrap">
  <?php screen_icon(); ?>
  <?php echo '<h2>' . __( 'The Publisher Desk', 'publisher_desk_dom' ) . '</h2>'; ?>
  <form name="publisher_desk_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
    <input type="hidden" name="publisher_desk_hidden" value="yes">
    <table class="form-table">
      <tr valign="top">
        <th scope="row"><label><?php _e( 'Publisher ID' ); ?></label></th>
        <td>
          <input type="text" name="publisher_desk_id" value="<?php echo $id; ?>" size="50">
          <p class="description">Your account manager will provide this value.</p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label><?php _e( 'Third-Party Framebusters' ); ?></label></th>
        <td>
          <?php if ( $framebusters == 1 ): ?>
            <input type="checkbox" name="publisher_desk_framebusters" value="1" checked="checked">
          <?php else: ?>
            <input type="checkbox" name="publisher_desk_framebusters" value="1">
          <?php endif; ?>
          <?php _e( 'Enable third-party framebusters' ); ?>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label><?php _e( 'Contextual Ads' ); ?></label></th>
        <td>
          <?php if ( $contextual == 1 ): ?>
            <input type="checkbox" name="publisher_desk_contextual" value="1" checked="checked">
          <?php else: ?>
            <input type="checkbox" name="publisher_desk_contextual" value="1">
          <?php endif; ?>
          <?php _e( 'Insert contextual ads below posts' ); ?>
        </td>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>
</div>
