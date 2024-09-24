$(document).ready(function() {
    $('.username').on('blur', function() {
        var user_name = $('#username').val();
        var user_id = $("#userid").val();
        if ($.trim(user_name) == '') {
            $("#err_username").html("This field is required.");
            $("#username").on('keyup', function() {
                $("#err_username").html("");
            });
            return false;
        } else {
            $.ajax({
                headers: {
                    'X-CSRF-Token': csrf_token
                },
                url: SITE_URL + '/common/check_username_duplication',
                type: "post",
                dataType: 'json',
                data: {
                    username: user_name,
                    userid: user_id
                },
                beforeSend: function() {},
                success: function(resp) {
                    if (resp.status == "exist" && resp.msg != "") {} else {}
                }
            });
        }
    });

    $('#admin_area').on('change', function() {
        var property_area    = $('#property_area').val();
        var total_build_area = $('#total_build_area').val();
        var admin_area       = $('#admin_area').val();
        var total_area       = parseInt(total_build_area) + parseInt(admin_area);

        if ($.trim(property_area) < total_area) {
            $('#err_admin_area').html('Addition of total build area,admin area should not be greater than total area');
            $('#admin_area').focus();
            $('#admin_area').on('change', function() {
                $('#err_admin_area').html('');
            });
            return false;
        }
    });
    $('#frm_post_property_step1').on('submit', function() {
        var property_type           = $('#property_type').val();
        var property_name           = $('#property_name').val();
        var description             = $('#description').val();
        var no_of_guest             = $('#no_of_guest').val();
        var no_of_bedrooms          = $('#no_of_bedrooms').val();
        var bathrooms               = $('#bathrooms').val();
        var no_of_beds              = $('#no_of_beds').val();
        var property_working_status = $('#property_working_status').val();
        var property_area           = $('#property_area').val();
        var total_build_area        = $('#total_build_area').val();
        var admin_area              = $('#admin_area').val();
        var good_storage            = $('#good_storage').val();
        var build_type              = $('#build_type').val();
        var address                 = $('.address').val();
        var postal_code             = $('#postal_code').val();
        var property_remarks        = $('#property_remarks').val();
        var custom_type             = $('input[name=custom_type]:checked').val();
        var management              = $('input[name=management]:checked').val();
        var currency                = $('#currency').val();
        var price                   = $('#price').val();
        var price_per_sqft          = $('#price_per_sqft').val();
        var no_of_slots             = $('#no_of_slots').val();
        var price_per               = $('#price_per').val();
        var slug                    = $('option:selected', '#property_type').attr('slug');
        var total_area              = parseInt(total_build_area) + parseInt(admin_area);
        var flag                    = 1;

        var office_private_room   = $("#office_private_room").prop("checked");
        var office_dedicated_desk = $("#office_dedicated_desk").prop("checked");
        var office_cubicles       = $("#office_cubicles").prop("checked");
        var no_of_room            = $('#no_of_room').val();
        var no_of_desk            = $('#no_of_desk').val();
        var no_of_cubicles        = $('#no_of_cubicles').val();
        var room_price            = $('#room_price').val();
        var desk_price            = $('#desk_price').val();
        var cubicles_price        = $('#cubicles_price').val();

        if ($.trim(postal_code) == '') {
            $('#err_postal_code').html('Please enter postal code');
            $('#postal_code').focus();
            $('#postal_code').on('change', function() {
                $('#err_postal_code').html('');
            });
            flag = 0;
        }
        if ($.trim(address) == '') {
            $('#err_address').html('Please enter address');
            $('.address').focus();
            $('.address').on('change', function() {
                $('#err_address').html('');
            });
            flag = 0;
        }
        if ($.trim(slug) == 'warehouse') {
            if ($.trim(price_per_sqft) == '') {
                $('#err_price_per_sqft').html('Please enter price per Sq.Ft');
                $('#price_per_sqft').focus();
                $('#price_per_sqft').on('change', function() {
                    $('#err_price_per_sqft').html('');
                });
                flag = 0;
            }
            if ($.trim(currency) == '') {
                $('#err_currency').html('Please enter currency');
                $('#currency').focus();
                $('#currency').on('change', function() {
                    $('#err_currency').html('');
                });
                flag = 0;
            }
            if ($.trim(no_of_slots) == '') {
                $('#err_no_of_slots').html('Please No. Of slots');
                $('#no_of_slots').focus();
                $('#no_of_slots').on('change', function() {
                    $('#err_no_of_slots').html('');
                });
                flag = 0;
            }
            if ($.trim(build_type) == '') {
                $('#err_build_type').html('Please select build type');
                $('#build_type').focus();
                $('#build_type').on('change', function() {
                    $('#err_build_type').html('');
                });
                flag = 0;
            }
            if ($.trim(good_storage) == '') {
                $('#err_good_storage').html('Please enter good storage');
                $('#good_storage').focus();
                $('#good_storage').on('change', function() {
                    $('#err_good_storage').html('');
                });
                flag = 0;
            }
            if ($.trim(admin_area) == '') {
                $('#err_admin_area').html('Please enter admin area');
                $('#admin_area').focus();
                $('#admin_area').on('change', function() {
                    $('#err_admin_area').html('');
                });
                flag = 0;
            } else if ($.trim(property_area) < total_area) {
                $('#err_admin_area').html('Addition of total build area,admin area should not be greater than total area');
                $('#admin_area').focus();
                $('#admin_area').on('change', function() {
                    $('#err_admin_area').html('');
                });
                flag = 0;
            }
            if ($.trim(total_build_area) == '') {
                $('#err_total_build_area').html('Please enter total build area');
                $('#total_build_area').focus();
                $('#total_build_area').on('change', function() {
                    $('#err_total_build_area').html('');
                });
                flag = 0;
            } else if ($.trim(property_area) < $.trim(total_build_area)) {
                $('#err_total_build_area').html('Total build up area should be less than total area ');
                $('#total_build_area').focus();
                $('#total_build_area').on('change', function() {
                    $('#err_total_build_area').html('');
                });
                flag = 0;
            }
            if ($.trim(management) == '') {
                $('#err_management').html('Please select management');
                $('.management').focus();
                $('.management').on('change', function() {
                    $('#err_management').html('');
                });
                flag = 0;
            }
            if ($.trim(custom_type) == '') {
                $('#err_custom_type').html('Please select custom type');
                $('.custom_type').focus();
                $('.custom_type').on('change', function() {
                    $('#err_custom_type').html('');
                });
                flag = 0;
            }
            if ($.trim(property_area) == '') {
                $('#err_property_area').html('Please enter area in Sq.Ft');
                $('#property_area').focus();
                $('#property_area').on('change', function() {
                    $('#err_property_area').html('');
                });
                flag = 0;
            }
            if ($.trim(property_working_status) == '') {
                $('#err_property_working_status').html('Please select working status');
                $('#property_working_status').focus();
                $('#property_working_status').on('change', function() {
                    $('#err_property_working_status').html('');
                });
                flag = 0;
            }
        }
        if ($.trim(slug) == 'office-space') {

            if( office_private_room == true || office_dedicated_desk == true || office_cubicles == true )
            {
                $("#err_office_has").html('');

                if( office_private_room == true ) {
                    
                    if ($.trim(no_of_room) == '') {
                        $('#err_no_of_room').html('Please enter no of room');
                        $('#no_of_room').focus();
                        $('#no_of_room').on('change', function() {
                            $('#err_no_of_room').html('');
                        });
                        flag = 0;
                    }
                    else {
                        $('#err_no_of_room').html('');
                    }

                    if ($.trim(room_price) == '') {
                        $('#err_room_price').html('Please enter room price');
                        $('#room_price').focus();
                        $('#room_price').on('change', function() {
                            $('#err_room_price').html('');
                        });
                        flag = 0;
                    }
                    else {
                        $('#err_room_price').html('');
                    }
                }

                if( office_dedicated_desk == true ) {
                    
                    if ($.trim(no_of_desk) == '') {
                        $('#err_no_of_desk').html('Please enter no of desk');
                        $('#no_of_desk').focus();
                        $('#no_of_desk').on('change', function() {
                            $('#err_no_of_desk').html('');
                        });
                        flag = 0;
                    }
                    else {
                        $('#err_no_of_desk').html('');
                    }

                    if ($.trim(desk_price) == '') {
                        $('#err_desk_price').html('Please enter desk price');
                        $('#desk_price').focus();
                        $('#desk_price').on('change', function() {
                            $('#err_desk_price').html('');
                        });
                        flag = 0;
                    }
                    else {
                        $('#err_desk_price').html('');
                    }
                }

                if( office_cubicles == true ) {
                    
                    if ($.trim(no_of_cubicles) == '') {
                        $('#err_no_of_cubicles').html('Please enter no of cubicles');
                        $('#no_of_cubicles').focus();
                        $('#no_of_cubicles').on('change', function() {
                            $('#err_no_of_cubicles').html('');
                        });
                        flag = 0;
                    }
                    else{
                        $('#err_no_of_cubicles').html('');
                    }

                    if ($.trim(cubicles_price) == '') {
                        $('#err_cubicles_price').html('Please enter cubicles price');
                        $('#cubicles_price').focus();
                        $('#cubicles_price').on('change', function() {
                            $('#err_cubicles_price').html('');
                        });
                        flag = 0;
                    }
                    else{
                        $('#err_cubicles_price').html('');
                    }
                }
            }
            else {
                $('#err_no_of_room').html('');
                $('#err_no_of_desk').html('');
                $('#err_no_of_cubicles').html('');

                $('#err_room_price').html('');
                $('#err_desk_price').html('');
                $('#err_cubicles_price').html('');

                $("#err_office_has").html('Please select any of the above option');
                flag = 0;
            }

            if ($.trim(currency) == '') {
                $('#err_currency').html('Please enter currency');
                $('#currency').focus();
                $('#currency').on('change', function() {
                    $('#err_currency').html('');
                });
                flag = 0;
            }
            if ($.trim(build_type) == '') {
                $('#err_build_type').html('Please select build type');
                $('#build_type').focus();
                $('#build_type').on('change', function() {
                    $('#err_build_type').html('');
                });
                flag = 0;
            }
            if ($.trim(admin_area) == '') {
                $('#err_admin_area').html('Please enter admin area');
                $('#admin_area').focus();
                $('#admin_area').on('change', function() {
                    $('#err_admin_area').html('');
                });
                flag = 0;
            } else if ($.trim(property_area) < total_area) {
                $('#err_admin_area').html('Addition of total build area,admin area should not be greater than total area');
                $('#admin_area').focus();
                $('#admin_area').on('change', function() {
                    $('#err_admin_area').html('');
                });
                flag = 0;
            }
            if ($.trim(total_build_area) == '') {
                $('#err_total_build_area').html('Please enter total build area');
                $('#total_build_area').focus();
                $('#total_build_area').on('change', function() {
                    $('#err_total_build_area').html('');
                });
                flag = 0;
            }
            if ($.trim(property_area) == '') {
                $('#err_property_area').html('Please enter area in Sq.Ft');
                $('#property_area').focus();
                $('#property_area').on('change', function() {
                    $('#err_property_area').html('');
                });
                flag = 0;
            }
            if ($.trim(property_working_status) == '') {
                $('#err_property_working_status').html('Please select working status');
                $('#property_working_status').focus();
                $('#property_working_status').on('change', function() {
                    $('#err_property_working_status').html('');
                });
                flag = 0;
            }
        }
        if ($.trim(slug) != 'warehouse' && $.trim(slug) != 'office-space') {
            if ($.trim(price) == '') {
                $('#err_price').html('Please enter price per night');
                $('#price').focus();
                $('#price').on('change', function() {
                    $('#err_price').html('');
                });
                flag = 0;
            }
            if ($.trim(currency) == '') {
                $('#err_currency').html('Please enter currency');
                $('#currency').focus();
                $('#currency').on('change', function() {
                    $('#err_currency').html('');
                });
                flag = 0;
            }
            if ($.trim(no_of_beds) == '') {
                $('#err_no_of_beds').html('Please enter no of no of beds');
                $('#no_of_beds').focus();
                $('#no_of_beds').on('change', function() {
                    $('#err_no_of_beds').html('');
                });
                flag = 0;
            }
            if ($.trim(bathrooms) == '') {
                $('#err_bathrooms').html('Please enter no of bathrooms');
                $('#bathrooms').focus();
                $('#bathrooms').on('change', function() {
                    $('#err_bathrooms').html('');
                });
                flag = 0;
            }
            if ($.trim(no_of_bedrooms) == '') {
                $('#err_no_of_bedrooms').html('Please enter no of bedrooms');
                $('#no_of_bedrooms').focus();
                $('#no_of_bedrooms').on('change', function() {
                    $('#err_no_of_bedrooms').html('');
                });
                flag = 0;
            }
            if ($.trim(no_of_guest) == '') {
                $('#err_no_of_guest').html('Please enter no of guest');
                $('#no_of_guest').focus();
                $('#no_of_guest').on('change', function() {
                    $('#err_no_of_guest').html('');
                });
                flag = 0;
            }
        }

        if ($.trim(description) == '') {
            $('#err_description').html('Please enter property description');
            $('#description').focus();
            $('#description').on('change', function() {
                $('#err_description').html('');
            });
            flag = 0;
        }
        if ($.trim(property_name) == '') {
            $('#err_property_name').html('Please enter property name');
            $('#property_name').focus();
            $('#property_name').on('change', function() {
                $('#err_property_name').html('');
            });
            flag = 0;
        }
        if ($.trim(property_type) == '') {
            $('#err_property_type').html('Please select property type');
            $('#property_type').focus();
            $('#property_type').on('change', function() {
                $('#err_property_type').html('');
            });
            flag = 0;
        }

        if (flag == 1) {
            showProcessingOverlay();
            return true;
        } else {
            return false;
        }
    });

    $('#frm_edit_property_step1').on('submit', function() {
        var property_type           = $('#property_type').val();
        var property_name           = $('#property_name').val();
        var description             = $('#description').val();
        var no_of_guest             = $('#no_of_guest').val();
        var no_of_bedrooms          = $('#no_of_bedrooms').val();
        var bathrooms               = $('#bathrooms').val();
        var no_of_beds              = $('#no_of_beds').val();
        var property_working_status = $('#property_working_status').val();
        var property_area           = $('#property_area').val();
        var total_build_area        = $('#total_build_area').val();
        var good_storage            = $('#good_storage').val();
        var admin_area              = $('#admin_area').val();
        var build_type              = $('#build_type').val();
        var address                 = $('.address').val();
        var postal_code             = $('#postal_code').val();
        var property_remarks        = $('#property_remarks').val();
        var custom_type             = $('input[name=custom_type]:checked').val();
        var management              = $('input[name=management]:checked').val();
        var currency                = $('#currency').val();
        var price                   = $('#price').val();
        var price_per_sqft          = $('#price_per_sqft').val();
        var no_of_slots             = $('#no_of_slots').val();
        var no_of_employee          = $('#no_of_employee').val();
        var price_per               = $('#price_per').val();
        var slug                    = $('option:selected', '#property_type').attr('slug');
        var total_cnt               = $("#total_cnt").val();
        var total_area              = parseInt(total_build_area) + parseInt(admin_area);
        var flag                    = 1;

        var office_private_room   = $("#office_private_room").prop("checked");
        var office_dedicated_desk = $("#office_dedicated_desk").prop("checked");
        var office_cubicles       = $("#office_cubicles").prop("checked");
        
        var no_of_room            = $('#no_of_room').val();
        var no_of_desk            = $('#no_of_desk').val();
        var no_of_cubicles        = $('#no_of_cubicles').val();
        
        var room_price            = $('#room_price').val();
        var desk_price            = $('#desk_price').val();
        var cubicles_price        = $('#cubicles_price').val();

        if ($.trim(postal_code) == '') {
            $('#err_postal_code').html('Please enter postal code');
            $('#postal_code').focus();
            $('#postal_code').on('change', function() {
                $('#err_postal_code').html('');
            });
            flag = 0;
        }
        if ($.trim(address) == '') {
            $('#err_address').html('Please enter address');
            $('.address').focus();
            $('.address').on('change', function() {
                $('#err_address').html('');
            });
            flag = 0;
        }
        if ($.trim(slug) == 'warehouse') {
            if ($.trim(price_per_sqft) == '') {
                $('#err_price_per_sqft').html('Please enter price per Sq.Ft');
                $('#price_per_sqft').focus();
                $('#price_per_sqft').on('change', function() {
                    $('#err_price_per_sqft').html('');
                });
                flag = 0;
            }
            if ($.trim(currency) == '') {
                $('#err_currency').html('Please enter currency');
                $('#currency').focus();
                $('#currency').on('change', function() {
                    $('#err_currency').html('');
                });
                flag = 0;
            }
            if ($.trim(no_of_slots) == '') {
                $('#err_no_of_slots').html('Please No. Of slots');
                $('#no_of_slots').focus();
                $('#no_of_slots').on('change', function() {
                    $('#err_no_of_slots').html('');
                });
                flag = 0;
            }
            if ($.trim(build_type) == '') {
                $('#err_build_type').html('Please select build type');
                $('#build_type').focus();
                $('#build_type').on('change', function() {
                    $('#err_build_type').html('');
                });
                flag = 0;
            }
            if ($.trim(good_storage) == '') {
                $('#err_good_storage').html('Please enter good storage');
                $('#good_storage').focus();
                $('#good_storage').on('change', function() {
                    $('#err_good_storage').html('');
                });
                flag = 0;
            }
            if ($.trim(admin_area) == '') {
                $('#err_admin_area').html('Please enter admin area');
                $('#admin_area').focus();
                $('#admin_area').on('change', function() {
                    $('#err_admin_area').html('');
                });
                flag = 0;
            } else if ($.trim(property_area) < total_area) {
                $('#err_admin_area').html('Addition of total build area,admin area should not be greater than total area');
                $('#admin_area').focus();
                $('#admin_area').on('change', function() {
                    $('#err_admin_area').html('');
                });
                flag = 0;
            }
            if ($.trim(total_build_area) == '') {
                $('#err_total_build_area').html('Please enter total build area');
                $('#total_build_area').focus();
                $('#total_build_area').on('change', function() {
                    $('#err_total_build_area').html('');
                });
                flag = 0;
            }
            if ($.trim(management) == '') {
                $('#err_management').html('Please select management');
                $('.management').focus();
                $('.management').on('change', function() {
                    $('#err_management').html('');
                });
                flag = 0;
            }
            if ($.trim(custom_type) == '') {
                $('#err_custom_type').html('Please select custom type');
                $('.custom_type').focus();
                $('.custom_type').on('change', function() {
                    $('#err_custom_type').html('');
                });
                flag = 0;
            }
            if ($.trim(property_area) == '') {
                $('#err_property_area').html('Please enter area in Sq.Ft');
                $('#property_area').focus();
                $('#property_area').on('change', function() {
                    $('#err_property_area').html('');
                });
                flag = 0;
            }
            if ($.trim(property_working_status) == '') {
                $('#err_property_working_status').html('Please select working status');
                $('#property_working_status').focus();
                $('#property_working_status').on('change', function() {
                    $('#err_property_working_status').html('');
                });
                flag = 0;
            }
        }
        if ($.trim(slug) == 'office-space') {
            
            if( office_private_room == true || office_dedicated_desk == true || office_cubicles == true )
            {
                $("#err_office_has").html('');

                if( office_private_room == true ) {
                    
                    if ($.trim(no_of_room) == '') {
                        $('#err_no_of_room').html('Please enter no of room');
                        $('#no_of_room').focus();
                        $('#no_of_room').on('change', function() {
                            $('#err_no_of_room').html('');
                        });
                        flag = 0;
                    }
                    else {
                        $('#err_no_of_room').html('');
                    }

                    if ($.trim(room_price) == '') {
                        $('#err_room_price').html('Please enter room price');
                        $('#room_price').focus();
                        $('#room_price').on('change', function() {
                            $('#err_room_price').html('');
                        });
                        flag = 0;
                    }
                    else {
                        $('#err_room_price').html('');
                    }
                }

                if( office_dedicated_desk == true ) {
                    
                    if ($.trim(no_of_desk) == '') {
                        $('#err_no_of_desk').html('Please enter no of desk');
                        $('#no_of_desk').focus();
                        $('#no_of_desk').on('change', function() {
                            $('#err_no_of_desk').html('');
                        });
                        flag = 0;
                    }
                    else {
                        $('#err_no_of_desk').html('');
                    }

                    if ($.trim(desk_price) == '') {
                        $('#err_desk_price').html('Please enter desk price');
                        $('#desk_price').focus();
                        $('#desk_price').on('change', function() {
                            $('#err_desk_price').html('');
                        });
                        flag = 0;
                    }
                    else {
                        $('#err_desk_price').html('');
                    }
                }

                if( office_cubicles == true ) {
                    
                    if ($.trim(no_of_cubicles) == '') {
                        $('#err_no_of_cubicles').html('Please enter no of cubicles');
                        $('#no_of_cubicles').focus();
                        $('#no_of_cubicles').on('change', function() {
                            $('#err_no_of_cubicles').html('');
                        });
                        flag = 0;
                    }
                    else{
                        $('#err_no_of_cubicles').html('');
                    }

                    if ($.trim(cubicles_price) == '') {
                        $('#err_cubicles_price').html('Please enter cubicles price');
                        $('#cubicles_price').focus();
                        $('#cubicles_price').on('change', function() {
                            $('#err_cubicles_price').html('');
                        });
                        flag = 0;
                    }
                    else{
                        $('#err_cubicles_price').html('');
                    }
                }
            }
            else {
                $('#err_no_of_room').html('');
                $('#err_no_of_desk').html('');
                $('#err_no_of_cubicles').html('');

                $('#err_room_price').html('');
                $('#err_desk_price').html('');
                $('#err_cubicles_price').html('');

                $("#err_office_has").html('Please select any of the above option');
                flag = 0;
            }

            if ($.trim(currency) == '') {
                $('#err_currency').html('Please enter currency');
                $('#currency').focus();
                $('#currency').on('change', function() {
                    $('#err_currency').html('');
                });
                flag = 0;
            }
            if ($.trim(build_type) == '') {
                $('#err_build_type').html('Please select build type');
                $('#build_type').focus();
                $('#build_type').on('change', function() {
                    $('#err_build_type').html('');
                });
                flag = 0;
            }
            if ($.trim(admin_area) == '') {
                $('#err_admin_area').html('Please enter admin area');
                $('#admin_area').focus();
                $('#admin_area').on('change', function() {
                    $('#err_admin_area').html('');
                });
                flag = 0;
            } else if ($.trim(property_area) < total_area) {
                $('#err_admin_area').html('Addition of total build area,admin area should not be greater than total area');
                $('#admin_area').focus();
                $('#admin_area').on('change', function() {
                    $('#err_admin_area').html('');
                });
                flag = 0;
            }
            if ($.trim(total_build_area) == '') {
                $('#err_total_build_area').html('Please enter total build area');
                $('#total_build_area').focus();
                $('#total_build_area').on('change', function() {
                    $('#err_total_build_area').html('');
                });
                flag = 0;
            }
            if ($.trim(property_area) == '') {
                $('#err_property_area').html('Please enter area in Sq.Ft');
                $('#property_area').focus();
                $('#property_area').on('change', function() {
                    $('#err_property_area').html('');
                });
                flag = 0;
            }
            if ($.trim(property_working_status) == '') {
                $('#err_property_working_status').html('Please select working status');
                $('#property_working_status').focus();
                $('#property_working_status').on('change', function() {
                    $('#err_property_working_status').html('');
                });
                flag = 0;
            }
        }
        if ($.trim(slug) != 'warehouse' && $.trim(slug) != 'office-space') {
            if ($.trim(price) == '') {
                $('#err_price').html('Please enter price per night');
                $('#price').focus();
                $('#price').on('change', function() {
                    $('#err_price').html('');
                });
                flag = 0;
            }
            if ($.trim(currency) == '') {
                $('#err_currency').html('Please enter currency');
                $('#currency').focus();
                $('#currency').on('change', function() {
                    $('#err_currency').html('');
                });
                flag = 0;
            }
            if ($.trim(no_of_beds) == '') {
                $('#err_no_of_beds').html('Please enter no of no of beds');
                $('#no_of_beds').focus();
                $('#no_of_beds').on('change', function() {
                    $('#err_no_of_beds').html('');
                });
                flag = 0;
            }
            if ($.trim(bathrooms) == '') {
                $('#err_bathrooms').html('Please enter no of bathrooms');
                $('#bathrooms').focus();
                $('#bathrooms').on('change', function() {
                    $('#err_bathrooms').html('');
                });
                flag = 0;
            }
            if ($.trim(no_of_bedrooms) == '') {
                $('#err_no_of_bedrooms').html('Please enter no of bedrooms');
                $('#no_of_bedrooms').focus();
                $('#no_of_bedrooms').on('change', function() {
                    $('#err_no_of_bedrooms').html('');
                });
                flag = 0;
            }
            if ($.trim(no_of_guest) == '') {
                $('#err_no_of_guest').html('Please enter no of guest');
                $('#no_of_guest').focus();
                $('#no_of_guest').on('change', function() {
                    $('#err_no_of_guest').html('');
                });
                flag = 0;
            }
            if (no_of_bedrooms < total_cnt) {
                var temp_cnt = $('#temp_cnt').val();
                if (temp_cnt == "") {
                    temp_cnt = 0;
                }
                cnt = parseInt(total_cnt) + parseInt(temp_cnt);
                if (no_of_bedrooms != cnt) {
                    swal('Number of bedrooms and sleeping arrangement should be a same');
                    flag = 0;
                }
            }
        }
        if ($.trim(description) == '') {
            $('#err_description').html('Please enter property description');
            $('#description').focus();
            $('#description').on('change', function() {
                $('#err_description').html('');
            });
            flag = 0;
        }
        if ($.trim(property_name) == '') {
            $('#err_property_name').html('Please enter property name');
            $('#property_name').focus();
            $('#property_name').on('change', function() {
                $('#err_property_name').html('');
            });
            flag = 0;
        }
        if ($.trim(property_type) == '') {
            $('#err_property_type').html('Please select property type');
            $('#property_type').focus();
            $('#property_type').on('change', function() {
                $('#err_property_type').html('');
            });
            flag = 0;
        }
        if (flag == 1) {
			showProcessingOverlay();
			return true;
        }
        else {
            return false;
        }
    });
});