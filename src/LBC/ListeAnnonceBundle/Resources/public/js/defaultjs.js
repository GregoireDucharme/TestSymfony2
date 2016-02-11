(function($)
{
	$( document ).ready(function() {
		$("input[name='search']").change(function()
		{
			$textSearch = $(this).val();
			console.log($textSearch);
			$(".searchWord").each(function(index) {
				console.log(index);
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
						console.log("titre=");
						console.log($(this).find("input[name=" + infoName + "]").val());
						break;

				}
			})
			return ($infoArray);
		}



		$("select[name='order']").change(function()
		{
			$orderBy = $(this).val();
			console.log($orderBy);
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
			console.log($infoArray);
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
	});
})(jQuery);