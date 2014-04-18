var Callforward = new function() {
	this.initalized = false;
	this.init = function() {
		//prevent multiple loads of this class which end up destroying content and rebinding a gazillon times
		if(this.initalized) {
			return false;
		}
		$('#module-Callforward form .input-group').each(function( index ) {
			$(this).find('input[type="text"]').prop('disabled',!$(this).find('input[type="checkbox"]').is(':checked'));
		});
		$('#cfringtimer').change(function() {
			Callforward.saveSettings({ringtimer: $(this).val()});
		});
		$('.cfnumber input[type="text"]').blur(function() {
			var el = $(this).prop("name");
			if($('.cfnumber input[data-el="'+el+'"]').is(':checked')) {
				if($(this).val() !== '') {
					Callforward.saveSettings({number: $(this).val(), type: $(this).data('type')});
				}
			}
		});
		$('.cfnumber input[type="checkbox"]').change(function() {
			var el = $(this).data("el");
			if(!$(this).is(':checked')) {
				$('#'+el).prop('disabled',true);
				if($('#'+el).val() !== '') {
					$('#'+el).val('');
					Callforward.saveSettings({number: '', type: $('#'+el).data('type')});
				}
			} else {
				$('#'+el).prop('disabled',false);
			}
		});
	};
	this.saveSettings = function(data) {
		data.ext = ext;
		$.post( "index.php?quietmode=1&module=callforward&command=settings", data, function( data ) {
			$('#module-Callforward .message').text(data.message).addClass('alert-'+data.alert).fadeIn('fast', function() {
				$('.masonry-container').packery();
				$(this).delay(2000).fadeOut('fast', function() {
					$('.masonry-container').packery();
				});
			});
		});
	};
};

//MUST REMAIN AT BOTTOM!
//This might not be needed as most browser seem to run doc ready anyways
//TODO: This should be in the higher up. each module should have this functionality from here on out!
$(function() {
	Callforward.init();
});
