(function($)
{
	$( document ).ready(function() {
		$("input[name='search']").change(function()
		{
			$textSearch = $(this).val();
			$(".searchWord").each(function(index) {
				if ($(this).html().toLowerCase().search($textSearch.toLowerCase()) == -1)
					$(this).parent().hide();
				else
					$(this).parent().show();					
			})
		});
		function getInfo(infoName)
		{
			$infoArray = [];
			$('.annonce').each(function() {
				switch (infoName)
				{
					case 'id':
					case 'prix':
						$infoArray.push(parseInt($(this).find("input[name=" + infoName + "]").val()));
						break;
					case 'titre':
					case 'lieu':
						$infoArray.push($(this).find("input[name=" + infoName + "]").val());
						break;

				}
			})
			return ($infoArray);
		}



		$("select[name='order']").change(function()
		{
			$orderBy = $(this).val();
			$infoArray = getInfo($orderBy);
			switch ($orderBy)
			{
				case 'id':
				case 'prix':
					$infoArray.sort(function sortInt(A, B)
					{
    					return (parseInt(A) - parseInt(B));
					});
					break;
				case 'titre':
				case 'lieu':
					$infoArray.sort();
					break;

			}
			$count = 0;
			$elementArray = [];
			while ($count < $infoArray.length)
			{
				$tmpElement = $("input[name='" + $orderBy + "'][value=\""+$infoArray[$count]+"\"]").parent().clone();
				$("input[name='" + $orderBy + "'][value=\""+$infoArray[$count++]+"\"]").parent().remove();
				$elementArray.push($tmpElement);
			}
			$count = 0;
			while ($elementArray[$count])
			{
				$("#body").append($elementArray[$count++]);
			}
		});



		$("button[name='reverse'").click(function() 
		{
			$elementArray = [];
			$('.annonce').each(function() {
				$tmpElement = $(this).clone();
				$(this).remove();
				$elementArray.push($tmpElement);
			})
			$count = 0;
			$elementArray.reverse();
			while ($elementArray[$count])
			{
				$("#body").append($elementArray[$count++]);
			}
		})

		var k = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65],  
 		n = 0;  
 		$(document).keydown(function (e) {  
 			if (e.keyCode == k[n++]) {  
 				if (n == k.length) {  
					$("#body").fadeIn(500).fadeOut(600).fadeIn(700).fadeOut(900).fadeIn(1000).fadeOut(2000).fadeIn(3000);
 					return 1  
 				}  
 			} else n = 0;
 		});  
	});
})(jQuery);