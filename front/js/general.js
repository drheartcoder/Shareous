function showAlert(msg,type){swal({title:msg,type:type,allowOutsideClick:true,html:true},function(){});}function geolocate(){var lat=lang='';if(navigator.geolocation){navigator.geolocation.getCurrentPosition(function(position){var geolocation={lat:position.coords.latitude,lng:position.coords.longitude};var circle=new google.maps.Circle({center:geolocation,radius:position.coords.accuracy});autocomplete.setBounds(circle.getBounds());});}}function initAutocomplete(){autocomplete=new google.maps.places.Autocomplete((document.getElementById('autocomplete')),{types:['geocode']});autocomplete.addListener('place_changed',fillInAddress);}function fillInAddress(){var componentForm={locality:'long_name',administrative_area_level_1:'long_name',country:'long_name',postal_code:'long_name'};var place=autocomplete.getPlace();for(var component in componentForm){document.getElementById(component).value='';document.getElementById(component).disabled=false;}if(place.formatted_address.length>0){getLatitudeLongitude(place.formatted_address);}for(var i=0;i<place.address_components.length;i++){var addressType=place.address_components[i].types[0];if(componentForm[addressType]){var val=place.address_components[i][componentForm[addressType]];document.getElementById(addressType).value=val;}}}function chk_validation(ref){var yourInput=$(ref).val();re=/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;var isSplChar=re.test(yourInput);if(isSplChar){var no_spl_char=yourInput.replace(/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');$(ref).val(no_spl_char);}}function getLatitudeLongitude(address){address=address||'India';geocoder=new google.maps.Geocoder();if(geocoder){geocoder.geocode({'address':address},function(results,status){if(status==google.maps.GeocoderStatus.OK){if(results[0].geometry.location.lat()!=""){$('#latitude').val(results[0].geometry.location.lat());}if(results[0].geometry.location.lng()!=""){$('#longitude').val(results[0].geometry.location.lng());}}});}}function makeStatusMessageHtml(status,message){str='<div class="alert alert-'+status+'">'+'<a aria-label="close" data-dismiss="alert" class="close" href="#">'+'×</a>'+message+'</div>';return str;}