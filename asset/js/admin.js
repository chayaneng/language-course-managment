( function( $ ) {

	$(document).ready( function() {
		// After delete row set url to default
		var href = window.location.href;
		if( href.indexOf('page=lang-course&delete') !== -1 ) {
			window.history.pushState({}, 'Course Managment', 'admin.php?page=lang-course');
		}

		$('.add_course').on('click', function() {
			var errors = {};
			var languageName, courseType, startingDate, startingTime, coursePrice, endingTime;
			languageName = $('.language_name').val();
			courseType = $('.course_type').val();
			startingDate = $('.starting_date').val();
			startingTime = $('.starting_time').val();
			coursePrice = $('.courseprice').val();
			endingTime = $('.courseendingtime').val();

			$.ajax({
				url : course.ajax_url,
				type: 'POST',
				data : {
					'action' 		: 'add_course',
					'languageName'	: languageName,
					'courseType'	: courseType,
					'startingDate'	: startingDate,
					'startingTime'	: startingTime,
					'price'			: coursePrice,
					'endingTime'	: endingTime
				},
				success : function( data ) {
					$('.lang_course tbody').append( $(data) );
					$('.language_name').val(' ');
					$('.course_type').val(' ');
					$('.starting_date').val(' ');
					$('.starting_time').val(' ');
					$('.courseprice').val(' ');
					$('.courseendingtime').val(' ');
				}
			});

			
			return false;
		});

		function edit_form_html( $this, value, id, refClass, refValue, edit_or_calcel ) {
			switch (refClass) {
				case 'language':
					if( edit_or_calcel == 'edit' ) {
						$html = '<input type="text" name="language-'+ id +'" class="language-'+ id +'" value="'+ refValue +'">';
						$html += '<input type="hidden" name="id-'+id+'" value="'+ id +'"/>';
					} else {
						$html = refValue;
					}
					$(value).html( $html );
					break;

				case 'coursetype':
					if( edit_or_calcel == 'edit' ) {
						$html = '<input type="text" name="coursetype-'+ id +'" class="coursetype-'+ id +'" value="'+ refValue +'">';
					} else {
						$html = refValue;
					}
					$(value).html( $html );
					break;

				case 'startingdate':
					if( edit_or_calcel == 'edit' ) {
						$html = '<input type="text" name="startingdate-'+ id +'" class="startingdate-'+ id +'" value="'+ refValue +'">';
						$html += "<script>";
						$html += "jQuery('.startingdate-" + id + "' ).datetimepicker({";
							$html += "lang:'en',";
							$html += "timepicker:false,";
							$html += "format:'Y/m/d',";
							$html += "formatDate:'Y/m/d',";
							$html += "minDate:'-1970/01/02'," ;
							$html += "maxDate:'+2050/01/02'"; 
						$html += "});";
						$html += "</script>";

					} else {
						$html = refValue;
					}
					$(value).html( $html );
					break;

				case 'coursetime':
					if( edit_or_calcel == 'edit' ) {
						$html = '<input type="text" name="coursetime-'+ id +'" class="coursetime-'+ id +'" value="'+ refValue +'">';

						$html += "<script>";
						$html += "jQuery('.coursetime-" + id + "' ).datetimepicker({";
							$html += "lang:'en',";
							$html += "datepicker:false,";
							$html += "format:'g:i A',"; 
							$html += "step:5,";
						$html += "});";
						$html += "</script>";

					} else {
						$html = refValue;
					}
					$(value).html( $html );
					break;

				case 'price':
					if( edit_or_calcel == 'edit' ) {
						$html = '<input type="text" name="price-'+ id +'" class="price-'+ id +'" value="'+ refValue +'">';
					} else {
						$html = refValue;
					}
					$(value).html( $html );
					break;

				case 'endingtime':
					if( edit_or_calcel == 'edit' ) {
						$html = '<input type="text" name="endingtime-'+ id +'" class="endingtime-'+ id +'" value="'+ refValue +'">';

						$html += "<script>";
						$html += "jQuery('.endingtime-" + id + "' ).datetimepicker({";
							$html += "lang:'en',";
							$html += "datepicker:false,";
							$html += "format:'g:i A',"; 
							$html += "step:5,";
						$html += "});";
						$html += "</script>";
					} else {
						$html = refValue;
					}
					$(value).html( $html );
					break;

				case 'edit_delete':
					if( edit_or_calcel == 'edit' ) {
						$this.siblings('a[href*=cancel]').show();
						$this.siblings('a[href*=delete]').hide();
						$this.hide();
					} else if( edit_or_calcel == 'cancel' ) {
						$this.siblings('a[href*=edit]').show();
						$this.siblings('a[href*=delete]').show();
						$this.hide();
					}
					break;
				default:
					var something = '';
					break;
			}
		}

		// Edit for showing
		$('.lang_course table').on('click', "a[href*=edit]", function(e) {
			e.preventDefault();

		    var $this 	= $(this),
		    	id 		= $this.attr('id').split('-').pop(),
		    	tr 		= $('.course-' + id ).find('td');
				
			$.each( tr, function( index, value ) {
				var refClass	= $( value ).attr('class'),
					refValue	= $(value).html();
				edit_form_html( $this, value, id, refClass, refValue, 'edit' );
			});
		});

		// cancel for showing
		$('.lang_course table').on('click', "a[href*=cancel]", function(e) {
			e.preventDefault();
		    var $this 	= $(this),
		    	id 		= $this.attr('id').split('-').pop(),
		    	tr 		= $('.course-' + id ).find('td');
				
			$.each( tr, function( index, value ) {
				var refClass	= $( value ).attr('class'),
					refValue	= $(value).find('input').val();

				edit_form_html( $this, value, id, refClass, refValue, 'cancel' );
			});
		});


	});

})(jQuery);