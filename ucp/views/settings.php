<div class="message alert" style="display:none;"></div>
<form role="form">
	<div class="form-group">
		<label for="cfringtimer"><?php echo _('CallForward Ringtimer') ?>:</label><br/>
		<select name="cfringtimer" id="cfringtimer" class="form-control">
			<option value="0">Default</option>
			<option value="-1" <?php echo ($ringtime == -1) ? 'selected' : ''?>>Always</option>
			<?php foreach($cfringtimes as $key => $value) { ?>
				<option value="<?php echo $key?>" <?php echo ($ringtime == $key) ? 'selected' : ''?>><?php echo $value?> <?php echo _('Seconds')?></option>
			<?php } ?>
		</select>
	</div>
	<label for=""><?php echo _('Unconditional')?></label></br>
	<div class="input-group cfnumber">
		<span class="input-group-addon">
			<input type="checkbox" id="call_forward_number_enable" name="call_forward_number_enable" data-el="call_forward" <?php echo !empty($CFU) ? 'checked' : ''?>>
		</span>
		<input type="text" class="form-control" id="call_forward" name="call_forward" data-type="CFU" value="<?php echo $CFU?>">
	</div><!-- /input-group -->
	<label for=""><?php echo _('Unavailable')?></label></br>
	<div class="input-group cfnumber">
		<span class="input-group-addon">
			<input type="checkbox" id="call_forward_unavailable_enable" name="call_forward_unavailable_enable" data-el="call_forward_unavailable" <?php echo !empty($CF) ? 'checked' : ''?>>
		</span>
		<input type="text" class="form-control" id="call_forward_unavailable" name="call_forward_unavailable" data-type="CF" value="<?php echo $CF?>">
	</div><!-- /input-group -->
	<label for=""><?php echo _('Busy')?></label></br>
	<div class="input-group cfnumber">
		<span class="input-group-addon">
			<input type="checkbox" id="call_forward_busy_number_enable" name="call_forward_busy_number_enable" data-el="call_forward_busy" <?php echo !empty($CFB) ? 'checked' : ''?>>
		</span>
		<input type="text" class="form-control" id="call_forward_busy" name="call_forward_busy" data-type="CFB" value="<?php echo $CFB?>">
	</div><!-- /input-group -->
</form>
