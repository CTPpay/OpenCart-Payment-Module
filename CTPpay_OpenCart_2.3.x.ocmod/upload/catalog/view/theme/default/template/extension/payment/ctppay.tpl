<form action="<?php echo $action; ?>" method="get">
  <input type="hidden" name="key" value="<?php echo $key; ?>">
  <input type="hidden" name="order" value="<?php echo $order; ?>">
  <input type="hidden" name="pay" value="<?php echo $pay; ?>">
  <input type="hidden" name="volume" value="<?php echo $volume; ?>">
  <input type="hidden" name="ref" value="<?php echo $ref; ?>">
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>