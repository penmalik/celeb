$(document).ready(function(){

    fetchData();

    // initialize datatable
    let table = new DataTable("#myTable");

    // fetch data from DB
    function fetchData(){
        // ajax function
        $.ajax({
            url: "handlers/crud.php?action=fetchAll",
            type: "POST",
            contentType: 'json',
            success: function(response){
                var num = 1;
                var data = response.data;
                // console.log(response);
                table.clear().draw();
                $.each(data, function(index, value){
                    var editBtn = '<Button type="button" class="btn editBtn" value="' + value.id + '"><i class="fa-solid fa-pen-to-square fa-xl"></i></Button>';
                    var deleteBtn = '<Button type="button" class="btn deleteBtn" value="' + value.id + '"><i class="fa-solid fa-trash fa-xl"></i></Button>';
                    var hiddenVal = '<input type="hidden" class="delete_image" value="' + value.car_image + '"/>';

                    table.row.add([
                        num,
                        value.name,
                        value.email,
                        value.car_name,
                        '<img src="images/' + value.car_image + '" style="width:50px;height:50px;border:2px solid gray;border-radius:50%;object-fit:cover;">',
                        value.state,
                        value.address,
                        "N" + value.price,
                        value.book_from,
                        value.book_until,
                        value.date_booked,
                        editBtn + deleteBtn + hiddenVal
                    ]).draw(false);
                    num++;
                });
            }
        });
    }

    // Update the value display when the slider is adjusted
    $("#priceSlider").on('input', function(){
        var price = this.value;
        // console.log(price);
        $("#priceValue").text('N' + price.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    });

    $("#editPriceSlider").on('input', function(){
        var price = this.value;
        // console.log(price);
        $("#editPriceValue").text('N' + price.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    });

    // preview image before uploading
    $("input.image").change(function(){
        var file = this.files[0];

        if(file){
            var url = URL.createObjectURL(file);
            $(this).closest('.row').find('.preview_img').attr('src', url);
            // console.log(file);
        }
        else{
            $("#errorToast").toast("show");
            $("#errorMsg").html('No image selected or image error');
        }

    });

    // logout logic
    $("#logout").on("click", function(e){
        e.preventDefault();
        // console.log("logout");
    
        $.ajax({
            url: "handlers/auth.php?action=logout",
            type: "POST",
            contentType: false,
            cache: false,
            processData: false,
            success: function(resposnse){
                // redirect to login page
                window.location.href = "log";
            },
            error: function(xhr, status, error) {
                console.error("Error during logout: " + error);  // Handle any error that occurs
            }
        })
    });

    // insert bookings to database
    $("#insertForm").on("submit", function(e){
        $("#insertBtn").attr("disabled", "disabled");
        e.preventDefault();
        $.ajax({
            url: "handlers/crud.php?action=insert",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response){
                var response = JSON.parse(response);
                // console.log(response);

                if(response.statusCode == 200){
                    $("#offcanvasAddBooking").offcanvas("hide");
                    $("#insertBtn").removeAttr('disabled');
                    $("#insertForm")[0].reset();
                    $(".preview_img").attr("src", "images/image.png");
                    $("#successToast").toast("show");
                    $("#successMsg").html(response.message);
                    fetchData();
                } else if(response.statusCode == 500){
                    $("#offcanvasAddBooking").offcanvas("hide");
                    $("#insertBtn").removeAttr('disabled');
                    $("#insertForm")[0].reset();
                    $(".preview_img").attr("src", "images/image.png");
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                }
            },
            error: function(xhr, status, error){
                console.error("Error: " + error);
                $("#errorToast").toast("show");
                $("#errorMsg").html("An Error occured, please try again. If error persist, contact admin");
            }
        });
    });

    // get a particular booking
    $("#myTable").on("click", ".editBtn", function(){
        
        var id = $(this).val();
        // console.log(`Edit button clicked \n Id: ${id}`)

        $.ajax({
            url: "handlers/crud.php?action=fetchSingle",
            type: "POST",
            dataType: "json",
            data: {
                id: id
            },
            success: function(response){
                // console.log(response);
                var data = response.data;
                $("#editForm #id").val(data.id);
                $("#editForm input[name='name']").val(data.name);
                $("#editForm input[name='email']").val(data.email);
                $("#editForm select[name='car']").val(data.car_name);
                $("#editForm select[name='state']").val(data.state);
                $("#editForm input[name='address']").val(data.address);
                $("#editForm input[name='from']").val(data.book_from);
                $("#editForm input[name='until']").val(data.book_until);

                $("#editForm .preview_img").attr("src", "images/" + data.car_image + "");
                $("#editForm #image_old").val(data.car_image);

                var price = parseFloat(data.price);
                
                $("#editForm input[name='price']").val(price);
                $("#editPriceSlider").val(price);
                $("#editPriceValue").text('N' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                // show edit form
                $("#offcanvasEditBooking").offcanvas("show");
            },
            error: function(xhr, status, error){
                console.error("Error: " + error);
                $("#errorToast").toast("show");
                $("#errorMsg").html("An error occured, try again");
            }
        });

    });

    // edit booking
    $("#editForm").on("submit", function(e){
        $("#editBtn").attr("disabled", "disabled");
        e.preventDefault();
        $.ajax({
            url: "handlers/crud.php?action=edit",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response){
                var response = JSON.parse(response);
                // console.log(response);
                if(response.statusCode == 200){
                    $("#offcanvasEditBooking").offcanvas("hide");
                    $("#editBtn").removeAttr("disabled");
                    $("#editForm")[0].reset();
                    $(".preview_img").attr("src", "images/image.png");
                    $("#successToast").toast("show");
                    $("#successMsg").html(response.message);
                    fetchData();
                } else if(response.statusCode == 500){
                    $("#offcanvasEditBooking").offcanvas("hide");
                    $("#editBtn").removeAttr("disabled");
                    $("#editForm")[0].reset();
                    $(".preview_img").attr("src", "images/image.png");
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                } else if(response.statusCode == 400){
                    $("#editBtn").removeAttr("disabled");
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                }
            },
            error: function(xhr, status, error){
                console.error("Error: " + error);
                 $("#editBtn").removeAttr("disabled");
                $("#errorToast").toast("show");
                $("#errorMsg").html("Error occured, try again");
            }
        });
    });

    // delete booking
    $("#myTable").on("click", ".deleteBtn", function(){
        if(confirm("Are you sure you want to delete selected booking? Action not reversible")){
            var id = $(this).val();
            var delete_image = $(this).closest("td").find(".delete_image").val();
            // console.log(`Id: ${id} \n Image_id: ${delete_image}`);

            $.ajax({
                url: "handlers/crud.php?action=delete",
                type: "POST",
                dataType: "json",
                data: {
                    id: id,
                    delete_image: delete_image
                },
                success: function(response){
                    // console.log(response);
                    if(response.statusCode == 200){
                        fetchData();
                        $("#successToast").toast("show");
                        $("#successMsg").html(response.message);
                    } else {
                        $("#errorToast").toast("show");
                        $("#errorMsg").html(response.message);
                    }
                },
                error: function(xhr, status, error){
                    console.error("Error: " + error);
                    $("#errorToast").toast("show");
                    $("#errorMsg").html("An error occured, try again");
                }
            });
            
        }
    });



});










