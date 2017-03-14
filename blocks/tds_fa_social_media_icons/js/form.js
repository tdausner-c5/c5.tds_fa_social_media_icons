/**
 *  
 */
(function($) {
	$(document).ready(function() {

		var iconStyles = '';		
		window.initIconStyles = function(iSt) {
			iconStyles = iSt;
			onChange();

			$('#sortable').sortable({
				placeholder: 'ui-state-highlight',
				axis: 'y',
				cursor: 'move',
				create: sortEvent,
				update: sortEvent
			});

			$('#iconShape, #iconColor, #iconSize, #hoverIcon, #iconMargin').change(onChange);
			/*
			 * setup URL validation
			 */
			$('#sortable input.ccm-input-text').change(function() {
				var $inp = $(this);
				var regex = new RegExp($inp.data('regex'));
				if (!$inp.val().match(regex)) {
					var t = $('#urlError').val().replace(/%s/, $inp.attr('id'));
					ConcreteAlert.error({
						message: t + '<br/><br/>' + $inp.attr('placeholder')
					});
				}
			});
		}
		
		var sortEvent = function(event, ui) {
			$('#sortOrder').val($(this).sortable('toArray').toString());
		};

		var onChange = function() {
			var im = $('#iconMargin').val();
			if (!im.match(/^(0|[1-9][0-9]*)$/)) {
				ConcreteAlert.error({
					message: $('#iconMarginError').val()
				});
			}
			else {
				$('#iconMarginError').hide();
				var hovAtts = $('#hoverIcon').val() != 'none' ? ('background: ' + $('#hoverIcon').val()) : '';
				$('style#iconStyles').html(
					iconStyles
						.replace(/%iconMargin%/g,	im)
						.replace(/%iconSize%/g, 	$('#iconSize').val())
						.replace(/%hoverAttrs%/g,	hovAtts)
						.replace(/%borderRadius%/g,	$('#iconShape').val() == 'round' ? $('#iconSize').val() / 2: 0)
				);
	
				var iColor = $('#iconColor').val();
				$('#icon-preview-container .social-icon').each(function() {
					var name = $(this).parent().attr('id').substr(1);
					if (iColor == 'logo') {
						iClass = 'social-icon-' + name;
					}
					else if (iColor == 'inverse') {
						iClass = 'social-icon-' + name + '-inverse';
					}
					else {
						iClass = 'social-icon-' + iColor;
					}
					$(this).attr('class', 'social-icon ' + iClass);
				});
			}
		};
	});
} (window.jQuery));