<?php
$locId = $_SESSION['locId'];
$result = getUsersForLocation($locId);
//print_r($result);
?>

<style>
    table {
        float: none;
        width: inherit;
        color: lightyellow;
    }

    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    th, td {
        padding: 1px;
    }

    th {
        background-color: dimgray;

    }

    /*=========datatable custom============*/
    label {
        color: #ffffff;
    }

    div.dataTables_wrapper div.dataTables_info {
        color: white;
    }

    .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
        background-color: #ffa314;
        border-color: #ffa314;
    }

    .pagination > li > a, .pagination > li > span {
        color: #222;
    }

    .table-striped > tbody > tr:nth-of-type(2n+1) {
        background-color: #000;
    }

    .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
        border: 1px solid #000;
    }

    .table-responsive {
        overflow-x: auto;
    }

    /*=========datatable custom============*/

</style>

<div class="">
    <div class="container">
        <!--        <div>-->
        <!--            <h1>THIS IS TEST PAGE</h1>-->
        <!--        </div>-->
        <div class="row">
            <div class="col-md-12">
                <h3 style="color:white;">ENTER QR CODE HERE:</h3>
                <input name="userIdentifier" id="userIdentifier" type="text" autofocus>
                <!--	              <button name="submit" id="clicksubmit" value="submit" type="submit">Submit</button>-->
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<h3 style="color: white;text-align:center;">CHECKED IN DETAILS</h3>
<div class="">
    <div class="container">
        <!--	<table style="border: 1px solid black; border-collapse: collapse; color:gold;">-->

        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0" style="border: 1px solid black; border-collapse: collapse; color:gold;">
                <thead>
                <tr>
                    <th>Sl No</th>
                    <th>Client ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <!-- <th>Check Type</th>-->
                    <th>Check IN Time</th>
                    <th>Check OUT Time</th>
                    <th>Total hours(in minutes) </th>
                    <th>Total hours left(in minutes)</th>>
                    <!--<th>Check In</th>
                    <th>Check Out</th>
                    <th>Total hrs</th>-->
                </tr>

                </thead>
                <tbody>

                <?php
                $index = 1;
                foreach ($result as $row) {

                    ?>
								<?php

                           // $data_1 = calculateTotalHrs($row['vofClientId'], $row['loginDate'], $locId);
                            
                            $credit = getCreditLeft($row['vofClientId']);
                        ?>
                    <tr>
                        <td><?php echo $index; ?></td>
                        <td><?php echo $row['vofClientId']; ?></td>
                        <td><?php echo $row['firstname'] . " " . $row['lastname']; ?></td>
                        <td><?php echo $row['loginDate']; ?></td>

                        <td><?php echo $row['checkIn']; ?></td>
                        
                        <td><?php 
                        
                        		if($row['checkout']!= "")
                        		{
                        			echo $row['checkout'];
                        		}
										else 
										{
											echo "NA";
										}                        
                        
                         	?>
                        </td>
							
                        <td><?php echo $credit['co_work_hours_limit'];?></td>
                        <td><?php
                        	 
											echo $credit['co_work_hours_left'];                        	 
                        	 
                        
                        
                        ?></td>
                      
                      
                    </tr>

                    <?php
                    $index++;
                }
                ?>


                </tbody>


            </table>


        </div>
    </div>
</div>

<div id="successModal" class="modal fade in">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <img id="checkedInUserImage" class="img-circle" src="" alt="Avatar">
            </div>
            <div id="welcomeMsgDiv" class="modal-body" style="color:black;">
                <div class="arrow-down"></div>
                <p>Welcome, <span id="checkedInUser"></span>!</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#successModal">CONTINUE
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade in" id="failureModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Code Scanning Failed!
                </h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" id="errorMsgDiv">
                <p>Please try again</p>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" id="failbutton" class="btn btn-primary" data-toggle="modal"
                        data-target="#failureModal">TRY AGAIN
                </button>
            </div>
        </div>
    </div>
</div>



<script src="jquery-1.11.2.min.js"></script>
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->

<!--Datatables-->
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('input', '#userIdentifier', function (e) {
            e.preventDefault();
            var userIdentifier = $("#userIdentifier").val();
            if (userIdentifier) {
                checkUser(userIdentifier);
            }


        });


        $(document).on('click', '#clicksubmit', function (e) {
            e.preventDefault();
            var userIdentifier = $("#userIdentifier").val();
            if (userIdentifier) {
                checkUser(userIdentifier);
            }
        });

    });
    function AddParameter(form, name, value) {
        var $input = $("<input />").attr("type", "hidden")
            .attr("name", name)
            .attr("value", value);
        form.append($input);
    }

    function checkUser(userIdentifier) {
        var dataString = {send: true, credential: userIdentifier};
        var post_url = "authenticate.php";
        $.ajax({
            url: post_url,
            data: dataString,
            type: "POST",
            dataType: 'json',
            beforeSend: function (xhr) {
                //showLoader();
            },
            success: function (data) {
                //hideLoader();
                if (data.success == true) {
                    //alert("You have successfully checked in!");
                    // $("#checkedInUser").html(data.firstname + " " + data.lastname);
//			            $("#checkedInUserImage").attr("src", data.avatar);
//						  $('#successModal').show();
//			           setTimeout(function(){
//						      $("#successModal").hide();
//						  }, 5000);
                    //$("#activeUsersTable > tbody").append("<tr><td><img src='"+string+data.avatar+"'></td><td>"+data.firstname+" "+data.lastname+"</td><td>"+data.company+"</td><td>"+data.checkInDateTime+"</td></tr>");


                    var post_url = "checkprofile.php";
                    var $form = $("<form/>").attr("id", "data_form")
                        .attr("action", post_url)
                        .attr("method", "post");
                    $("body").append($form);

                    //Append the values to be send
                    AddParameter($form, "qrcode", userIdentifier);

                    //Send the Form
                    $form[0].submit();
                }
                else {
                    //alert("The credentials not match!");
                    $('#failureModal').show();
                    setTimeout(function () {
                        $("#failureModal").hide();
                    }, 5000);

                }
                $("#userIdentifier").val('');
                $("#userIdentifier").focus();
            }

        });//end of ajax
        $("#userIdentifier").focus();

    }


</script>
