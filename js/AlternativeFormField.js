(function($){
	
	function showHideAlternativeValue(el) {
		var select = $('.AlternativeFormFieldSelectedValue select', el),
				input = $('.AlternativeFormFieldAlternativeValue', el),
				val = select.hasClass('has-chzn') ? select.chosen().val() : select.val();
		
		console.log("VAL ", val)

		input.css('display', (val == 'Other') ? 'block' : 'none');
	
		if(!select.hasClass('has-chzn')) {
			applyChosen(select);
		}

	}

	function applyChosen(el) {
		if(el.is(':visible')) {
			
			el.addClass('has-chzn').chosen({
				allow_single_deselect: true,
				disable_search_threshold: 20
			});

			var title = el.prop('title');

			if(title) {
				el.siblings('.chzn-container').prop('title', title);
			}
		} else {
			setTimeout(function() {
				// Make sure it's visible before applying the ui
				el.show();
				applyChosen(el); }, 
			500);
		}
	};
	
	$('.AlternativeFormField').entwine({
		onadd: function(){
			var self = this;
			
			// Add listener
			self.find('.AlternativeFormFieldSelectedValue select').on('change', function() {
				showHideAlternativeValue.call(self);
			});

			// Initial setup
			showHideAlternativeValue.call(self);
			
		}

	});

})(jQuery);