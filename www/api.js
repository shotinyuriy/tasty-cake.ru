function loadCategories() {
	$.ajax({
		url: "./core/c-category.php",
		data: {},
		success: function( data ) {
			$( "#categories_menu" ).html( data );

			$( "div.menu-category" ).click ( function ( event ) {
				var categoryId = $( this );
				categoryId = categoryId ? categoryId[0].id : undefined;
				if( categoryId ) {
					$.ajax({
							type: "GET",
							url: "./core/set-current-category.php",
							data: { categoryId: categoryId }
						})
						.done( function( data ) {
							window.location.href = window.home_href+"menu";
						})
						.fail ( function( data, textStatus ) {
							alert( "Ошибка при выборе категории!" );
						});
				}
			});

			$("#recommended-button").addClass("disabled");
			$("#popular-button").removeClass("disabled");
		}
	});
}

$( document ).ready( function() {

	loadCategories();

	$("#popular-button").click(function(event) {
		event.preventDefault();
		$.ajax({
			url: "./core/c-good.php",
			data: {method: 'popular'},
			success: function (data) {
				$("#categories_menu").html(data);
				$("#recommended-button").removeClass("disabled");
				$("#popular-button").addClass("disabled");
			}
		});
	});

	$("#recommended-button").click(function(event) {
		event.preventDefault();
		loadCategories();
	});
});