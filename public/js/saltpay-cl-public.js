(function( $ ) {
	'use strict';
	$(document).ready(function(){
		var $saltpayLoans = $('.saltpay-loans');
		if($saltpayLoans.length){
			$('body').on('click', '.saltpay-loans .loan-info-toggle', function(e){
				$(this).toggleClass('toggled');
				$(this).closest('.loan').find('.loan-info').toggleClass('toggled');
			});
		}
	});
})( jQuery );
