document.addEventListener('DOMContentLoaded', function() {
  var fetchButton = document.getElementById('fetch');

	String.prototype.capitalizeFirstLetter = function() {
		return this.charAt(0).toUpperCase() + this.slice(1);
	}
      
  fetchButton.addEventListener('click', function() {

    chrome.tabs.getSelected(null, function(tab) {
      d = document;

			chrome.tabs.executeScript(
				null,
				{
					code: '\
					var merchant = window.location.hostname.split(\'.\')[1];\
					var prod_name = \'\', prod_price = \'\', prod_desc = \'\', a_tags = \'\', category = \'\', brand = \'\', gender = \'\', img_links = [], colors = [];\
					\
					if(merchant == \'koovs\'){\
						prod_name = document.getElementById(\'prod_name_hide\').value;\
						prod_price = document.getElementsByClassName(\'product-price\')[0].getElementsByTagName(\'strong\')[0].innerHTML;\
						prod_desc = document.getElementById(\'product-desc\').innerText;\
						a_tags = document.getElementsByClassName(\'thumb-views\')[0].getElementsByTagName(\'a\');\
						category = document.getElementById(\'product-desc\').getElementsByClassName(\'short_detail\')[0].getElementsByTagName(\'a\')[0].innerText;\
						brand = document.getElementById(\'product-desc\').getElementsByClassName(\'short_detail\')[0].getElementsByTagName(\'a\')[1].innerText;\
						gender = document.getElementById(\'breadcrumb\').getElementsByClassName(\'breadcrumb-link\')[1].getElementsByTagName(\'span\')[0].innerText;\
						for(var i=0; i<a_tags.length; i++){\
							var rel = a_tags[i].rel.replace(/\",\"/g,", ");\
							img_links.push(rel.split(\',\')[0].split(\'\":\"\')[1]);\
						}\
						colors.push(document.getElementById(\'selected_color_label\').innerText);\
					}\
					else if(merchant == \'indianroots\'){\
						prod_name = document.getElementsByTagName(\'h1\')[0].innerText;\
						prod_price = document.querySelectorAll(\'[itemprop]\')[1].childNodes[0].data.replace(/^\\D+/g, \'\');\
						prod_desc = document.querySelector(\'span[itemprop=\"description\"]\').innerText;\
						category = \'\';\
						brand = document.getElementsByClassName(\'ProContentDetails\')[0].getElementsByTagName(\'a\')[0].innerText;\
						gender = document.querySelector(\'.nav .l1-active\') ? document.querySelector(\'.nav .l1-active\').getElementsByTagName(\'a\')[0].innerText : "";\
						img_links.push(document.getElementById(\'zoom1\').href);\
						color_patterns = prod_desc.match(/Colour:[a-zA-Z ]*/);\
						if(color_patterns != null){\
							colors.push(color_patterns[0].replace(\'Colour:\',\'\').trim());\
						}\
					}\
					else if(merchant == \'limeroad\'){\
						prod_name = document.getElementsByClassName(\'p_name\')[0].innerText;\
						prod_price = document.getElementsByClassName(\'sPr\')[0].innerText;\
						prod_desc = document.getElementsByClassName(\'product_desc\')[0].innerText;\
						breadcrumbs = document.querySelectorAll(\'li[itemtype=\"http://data-vocabulary.org/Breadcrumb\"]\');\
						category = breadcrumbs[breadcrumbs.length - 1].innerText.trim();\
						brand = document.getElementsByClassName(\'sUsrNme\')[0].innerText;\
						gender = \'Women\';\
						img_links.push(document.getElementsByClassName(\'jqzoom\')[0].href);\
						color_tags = document.querySelectorAll(\'a[data-key=\"color\"]\');\
						var i;\
						for (i = 0; i < color_tags.length; i++) {\
							colors.push(color_tags[i].innerText);\
						}\
					}\
					else if(merchant == \'abof\'){\
						prod_name = document.getElementsByClassName(\'product-detail__title\')[0].innerHTML.trim();\
						prod_price = document.getElementsByClassName(\'product-detail__price--original\')[0].innerText.trim();\
						prod_desc = document.getElementsByClassName(\'product-detail__tab-content\')[0].getElementsByTagName(\'p\')[0].innerText;\
						breadcrumbs = document.getElementsByClassName(\'breadcrumbs\')[0].getElementsByTagName(\'li\');\
						category = breadcrumbs[breadcrumbs.length - 2].innerText;\
						brand = prod_name.split(\' \')[0];\
						gender = breadcrumbs[0].innerText;\
						img_links.push(document.getElementsByClassName(\'carousel__product-img\')[0].getElementsByTagName(\'img\')[0].src);\
					}\
					else if(merchant == \'amazon\'){\
						prod_name = document.getElementById(\'productTitle\').innerHTML.trim();\
						var price_tag =  document.getElementById(\'priceblock_saleprice\');\
						if (typeof(price_tag) == \'undefined\' || price_tag == null){\
							price_tag =  document.getElementById(\'priceblock_ourprice\');\
						}\
						prod_price = price_tag.innerHTML.match(/[\\d,]+\\.?\\d*/)[0];\
						desc_items = document.getElementById(\'feature-bullets\').getElementsByClassName(\'a-list-item\');\
						for(i=0; i<desc_items.length; i++){\
							prod_desc += (desc_items[i].innerHTML.trim() + \'\\n\')\
						}\
						category_tags = document.getElementsByClassName(\'zg_hrsr_ladder\')[0].getElementsByTagName(\'a\');\
						category = category_tags[category_tags.length - 1].innerHTML;\
						brand = document.getElementById(\'brand\').innerHTML;\
						for(i=0; i<category_tags .length; i++){\
							gender_text = category_tags[i].innerHTML.trim();\
							if(gender_text == \'Men\' || gender_text == \'Women\' || gender_text == \'Boys\' || gender_text == \'Girls\'){\
								gender = gender_text;\
							}\
						}\
						img_links.push(document.getElementById(\'landingImage\').src)\
					}\
					else if(merchant == \'jabong\'){\
						prod_name = document.getElementsByClassName(\'product-title\')[0].innerHTML.trim();\
						prod_price = document.getElementsByClassName(\'actual-price\')[0].innerHTML.trim();\
						prod_desc = document.getElementsByClassName(\'prod-disc\')[0].innerText;\
						breadcrumbs = document.getElementsByClassName(\'breadcrumb\')[0].getElementsByTagName(\'span\');\
						category = breadcrumbs[breadcrumbs.length - 1].innerText;\
						brand = document.getElementsByClassName(\'brand\')[0].innerHTML;\
						gender = breadcrumbs[1].innerText;\
						img_links.push(document.getElementsByClassName(\'product-image\')[0].getElementsByClassName(\'primary-image first\')[0].src);\
						labels = document.querySelectorAll(\'.prod-main-wrapper li\');\
						var i;\
						for (i = 0; i < labels.length; i++) {\
						    if(labels[i].querySelector(\'label\').innerText == \'Color\'){\
								colors.push(labels[i].querySelector(\'span\').innerText);\
							}\
						}\
					}\
					if(gender == "Women" || gender == "Girls"){\
						gender = "Female";\
					}\
					else if(gender == "Men" || gender == "Boys"){\
						gender = "Male";\
					}\
					var r = [prod_name, prod_price, prod_desc, img_links, merchant, category, brand, gender, colors];\
					r;\
					'
				},
				function(results){
					//console.log(results);
					document.getElementById('name').value = results[0][0].capitalizeFirstLetter();
					document.getElementById('price').value = results[0][1].replace(/,/g,"");
					document.getElementById('desc').innerHTML = results[0][2].capitalizeFirstLetter();
					document.getElementById('images').innerHTML = results[0][3];
					document.getElementById('url').value = tab.url;
					document.getElementById('merchant').value = results[0][4];
					document.getElementById('category').value = results[0][5].capitalizeFirstLetter();
					document.getElementById('brand').value = results[0][6].capitalizeFirstLetter();
					document.getElementById('gender').value = results[0][7];
					
					for(cnt in results[0][8]){
						var input = document.getElementById('color' + (parseInt(cnt) + 1));
						if(input != null){
							input.value = results[0][8][cnt].match(/multi/i) ? 'Multi' : results[0][8][cnt];
						}
					}
					
					var imagesDiv = document.getElementsByClassName('images')[0];

					//Remove previously added image tags
					var images = imagesDiv.getElementsByTagName('img');
					while(images.length > 0){
						images[0].parentNode.removeChild(images[0]);
					}
					
					//Remove previously added image url input tags
					var image_urls = imagesDiv.getElementsByClassName('prod_image');
					while(image_urls.length > 0){
						image_urls[0].parentNode.removeChild(image_urls[0]);
					}
					
					
					//Add image tags and image url input tags
					for(cnt in results[0][3]){
						var image = document.createElement('img');
						image.src=results[0][3][cnt];
						imagesDiv.appendChild(image);
						
						var input = document.createElement('input');
						input.value = results[0][3][cnt];
						input.name = "image" + cnt;
						input.className = "prod_image";
						imagesDiv.appendChild(input);
					}
					
				}
			);


    });
  }, false);
  
  var saveButton = document.getElementById('save');
  
  saveButton.addEventListener('click', function() {

			document.getElementById('response').innerHTML = "Saving...";

			var kvpairs = [];
			var input_elements = document.getElementsByTagName('input');
			for (var i = 0; i < input_elements.length; i++ ) {
				 var e = input_elements[i];
				 kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
			}
			var select_elements = document.getElementsByTagName('select');
			for (var i = 0; i < select_elements.length; i++ ) {
			  var e = select_elements[i];
			  kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
			}
			var textarea_elements = document.getElementsByTagName('textarea');
			for (var i = 0; i < textarea_elements.length; i++ ) {
				 var e = textarea_elements[i];
				 kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
			}

			var queryString = kvpairs.join("&");

		  	var url = "http://stylist.istyleyou.in/product/create";
	  		//var url = "http://stylist.istyleyou.loc/product/create";
			url = url + "?" + queryString;
			//alert(url);
			console.log(url);
			
			var xmlDoc = new XMLHttpRequest();
			xmlDoc.open('GET', url, true);

			xmlDoc.onreadystatechange = function() {
				if (xmlDoc.readyState === 4 && xmlDoc.status === 200) {
					console.log(xmlDoc.responseText);
					console.log(xmlDoc.responseText[0]);

					var response = JSON.parse(xmlDoc.responseText);
					console.log(response);
					console.log(response[0]);
					console.log(response[1]);

					if(response[0])
					{
						document.getElementById('response').innerHTML = "<a href='" + response[1] + "' target='New'>Check product</a>";
					}
					else{
						document.getElementById('response').innerHTML = "<label style='color:red;'>" + response[1] + "</label>";
					}
				}
			}

			xmlDoc.send();
			
	}, false);
	
}, false);
