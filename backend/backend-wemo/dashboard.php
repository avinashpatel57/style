<?php require'databaseconnect.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>I Style You</title>

  <link href="css/style.default.css" rel="stylesheet">


  <style>
.stick {
  position: fixed;
  top: 65px;
  margin-left: 900px;
  width: 100%;
  z-index: 999;
}
table.dataTable.select tbody tr,
table.dataTable thead th:first-child {
  cursor: pointer;

}


</style>
 <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.9/css/dataTables.bootstrap.min.css">
  
  
  
  <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.9/js/dataTables.bootstrap.min.js"></script>

  <script type="text/javascript" class="init">

//
// Updates "Select all" control in a data table
//
function updateDataTableSelectAllCtrl(table){
   var $table             = table.table().node();
   var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
   var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
   var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

   // If none of the checkboxes are checked
   if($chkbox_checked.length === 0){
      chkbox_select_all.checked = false;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If all of the checkboxes are checked
   } else if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = true;
      }
   }
}

$(document).ready(function (){
   // Array holding selected row IDs
   var rows_selected = [];
   var table = $('#example').DataTable({
     
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
             return '<input type="checkbox" id="userid" value="">';
         }
      }],
      'order': [1, 'asc'],
      'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
      }
   });

   // Handle click on checkbox
   $('#example tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');

      // Get row data
      var data = table.row($row).data();

      // Get row ID
      var rowId = data[2];

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         rows_selected.splice(index, 1);
      }

      if(this.checked){
         $row.addClass('selected');
      } else {
         $row.removeClass('selected');
      }

      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(table);

      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#example').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('#example thead input[name="select_all"]').on('click', function(e){
      if(this.checked){
         $('#example tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#example tbody input[type="checkbox"]:checked').trigger('click');
      }

      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   table.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(table);
   });

   // Handle form submission event 
   $('#frm-example').on('submit', function(e){

      var form = this;

      var $table             = table.table().node();
      var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);

      if($chkbox_checked.length === 0){
         alert("Please select at least one record");
        return false;
     }

 var ids = $.map(table.rows('.selected').data(), function (item) {
        return item[1]
    });
 
      // Iterate over all selected checkboxes
      $.each(rows_selected, function(index, rowId){
         // Create a hidden element 
         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'userid[]')
                .val(rowId)
         );
		 
	 $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'ids[]')
                .val(ids)
         );
        


      });

 // FOR DEMONSTRATION ONLY     
      
      // Output form data to a console     
     
   });


});
  </script>
  <script type="text/javascript">
 $(document).ready(function() {
    var s = $("#send");
            var pos = s.position();                      
            $(window).scroll(function() {
                var windowpos = $(window).scrollTop()+60;   
                if (windowpos >= pos.top) {
                    s.addClass("stick");
                } else {
                    s.removeClass("stick");   
                }
            });
});
  </script>
</head>

<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>

  <div class="leftpanel">

    <div class="logopanel">
        <h1><span>[</span> I Style You <span>]</span></h1>
    </div><!-- logopanel -->

    <div class="leftpanelinner">

        <!-- This is only visible to small devices -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">
            <div class="media userlogged">
                <img alt="" src="images/photos/loggeduser.png" class="media-object">
                <div class="media-body">
                    <h4>John Doe</h4>
                    <span>"Life is so..."</span>
                </div>
            </div>

            <h5 class="sidebartitle actitle">Account</h5>
            <ul class="nav nav-pills nav-stacked nav-bracket mb30">
              <li><a href="query.php?action=logout"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
            </ul>
        </div>

      <h5 class="sidebartitle">Navigation</h5>
      <ul class="nav nav-pills nav-stacked nav-bracket">
        <li class="active"><a href="dashboard.php"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
        <li><a href="create_look.php"><i class="fa fa-envelope-o"></i> <span>Create Look</span></a></li>
        <li><a href="look_list.php"><i class="fa fa-envelope-o"></i> <span>List All</span></a></li>
        <li><a href="product_list.php"><i class="fa fa-envelope-o"></i> <span>View Products</span></a></li>
        <li><a href="create_stylist.php"><i class="fa fa-envelope-o"></i> <span>Create Stylist</span></a></li>
        
        <li><a href="list_stylist.php"><i class="fa fa-envelope-o"></i> <span>See Stylist</span></a></li>
        <li><a href="list_users.php"><i class="fa fa-envelope-o"></i> <span>See All Users</span></a></li>
        
        
      </ul>

    

    </div><!-- leftpanelinner -->
  </div><!-- leftpanel -->

  <div class="mainpanel">

    <div class="headerbar">

      <a class="menutoggle"><i class="fa fa-bars"></i></a>

      <form class="searchform" action="" method="post">
        <input type="text" class="form-control" name="keyword" placeholder="Search here..." />
      </form>

      <div class="header-right">
        <ul class="headermenu">
          
          
        
          <li>
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <img src="images/photos/loggeduser.png" alt="" />
                John Doe
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li><a href="query.php?action=logout"><i class="glyphicon glyphicon-log-out"></i> Log Out</a></li>
              </ul>
            </div>
          </li>
          
        </ul>
      </div><!-- header-right -->

    </div><!-- headerbar -->

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> Dashboard <span>Subtitle goes here...</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <li><a href="dashboard.php">istyleyou</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </div>
    </div>
	

     <div class="contentpanel">
 <div class="row">
<!--image code 1-->


        <div class="col-sm-6 col-md-3">
       
           
           
                <div class="row">
                  <div class="col-xs-12">
				  <div class="form-group">

                    <label class="control-label">No. Of Stylist</label>
				 <?php 
				 $sql="select * from stylists";
				 $res=mysql_query($sql);
				 $stylish=mysql_num_rows($res);
				 
				 ?>
			  </div>
			<div class="form-group">
			<?php 
			 echo $stylish;
			 ?>
		 </div>
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                  <div class="col-xs-6">
                  
                  </div>

                  <div class="col-xs-6">
                  
                  </div>
                </div><!-- row -->

            
         
        </div><!-- col-sm-6 -->
	
       <div class="col-sm-6 col-md-3">
       
           
           
                <div class="row">
                  <div class="col-xs-12">
				     <div class="form-group">

                    <label class="control-label">No. Of Look</label>
		
			 <?php 
				 $sql="select * from looks";
				 $res=mysql_query($sql);
				 $totallook=mysql_num_rows($res);
				 
				 ?>
			
				 	</div>
					<div class="form-group">
			<?php 
			 echo $totallook;
			 ?>
		 </div>
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                  <div class="col-xs-6">
                  
                  </div>

                  <div class="col-xs-6">
                  
                  </div>
                </div><!-- row -->

            
         
        </div><!-- col-sm-6 -->
	
		
		       <div class="col-sm-6 col-md-3">
       
           
           
                <div class="row">
                  <div class="col-xs-12">
				  
			 <div class="form-group">

                    <label class="control-label">No. Of Users</label>
			 <?php 
				 $sql="select * from userdetails";
				 $res=mysql_query($sql);
				 $users=mysql_num_rows($res);
				 
				 ?>
				 			  
                       </div>
					   <div class="form-group">
			<?php 
			 echo $users;
			 ?>
		 </div>
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                  <div class="col-xs-6">
                  
                  </div>

                  <div class="col-xs-6">
                  
                  </div>
                </div><!-- row -->

            
         
        </div><!-- col-sm-6 -->
	
	
      </div><!-- row -->
       <div class="row">
<!--image code 1-->

      </div><!-- row -->
    
          	  <h4>Pending Notification/ Request</h4>
                 <?php
                 if(isset($_REQUEST['noskip']))
                     echo '<a href="' . $_SERVER['PHP_SELF'] . '">Skip old records and test users</a>';
                 else
                     echo '<a href="' . $_SERVER['PHP_SELF'] . '?noskip=1">Skip no records</a>';
                 ?>
                <div class="row">

                  <div>
                    <form name="frm-example" id="frm-example" method="POST" action="look_list.php">
                      <table id="example" class="display select" cellspacing="0" width="100%" >
                        <thead>
                          <tr>
                               <th><input name="select_all" value="1" type="checkbox"></th>
                              <th>Request id</th>
                              <th>Client id</th>
                              <th>Client name</th>
                              <th>Age</th>
                              <th>Body type</th>
                              <th>Occasion</th>
                              <th>Budget</th>
                              <th>Date</th>
                              <th>Stylist</th>
                              <th>Message</th>
                              <th>Request Type</th>
                           </tr>
                        </thead>
                        <tfoot>
                      <tr>
                          <th><input name="select_all" value="1" type="checkbox"></th>
                          <th>Request id</th>
                          <th>Client id</th>
                          <th>Client name</th>
                          <th>Age</th>
                          <th>Body type</th>
                          <th>Occasion</th>
                          <th>Budget</th>
                          <th>Date</th>
                          <th>Stylist</th>
                          <th>Message</th>
                          <th>Request Type</th>
                      </tr>
                    </tfoot>
                        <tbody>
          		<?php
                    $skip_records_clause = '';
                    if(!isset($_REQUEST['noskip'])){
                        $skip_users = '132, 141, 606, 515, 520, 292, 402, 584';
                        $skip_records_clause = 'AND u.user_id not in (' . $skip_users . ')
                                                AND sr.id >= 747';
                    }
          			  $query='select sr.id, sr.user_id, u.username, u.age, u.bodytype, lo.name, lb.name, sr.created_at,
                                      s.name, sr.description, le.name
                              from style_requests sr
                              join userdetails u on sr.user_id = u.user_id
                              join stylists s on u.stylish_id = s.stylish_id
                              left join style_requests_recommendations srr on sr.id = srr.style_request_id
                              join lu_budget lb on sr.budget_id = lb.id
                              join lu_occasion lo on sr.occasion_id = lo.id
                              join lu_entity_type le on sr.entity_type_id = le.id
                              where srr.recommendation_id is NULL
                              ' . $skip_records_clause . '
                              ORDER BY sr.id DESC';
                //echo $query;
                  	$res = mysql_query($query);			 
          			 	$numRows = mysql_num_rows($res);

          				if($numRows!=0){
          					$id=1;
          				while($resultSet = mysql_fetch_array($res))

          					{

          					?>
          			  <tr>
                      <td></td>
                      <td><?php echo $resultSet[0] ?></td>
          				<td><?php echo $resultSet[1]?></td>
          				<td><?php echo $resultSet[2]?></td>
          				<td><?php echo $resultSet[3]?></td>
          				<td><?php echo $resultSet[4]?></td>
          				<td><?php echo $resultSet[5]?></td>
          				<td><?php echo $resultSet[6]?></td>
          				<td><?php echo $resultSet[7]?></td>
          				<td><?php echo $resultSet[8]?></td>
          				<td><?php echo $resultSet[9]?></td>
          				<td><?php echo $resultSet[10]?></td>
                              </tr>
                          	<?php }}?>	
                        </tbody>
                     </table>
                    </div><!-- table-responsive -->
                    <div class="clearfix mb30"></div>
                </div><!-- row -->

    
    <div id="send" class="stick">
 <button type="submit" name="send" class="btn btn-success" >Send</button>
</div>



    </form>
    <?php
    if(isset($_SESSION["users"]))
        unset($_SESSION["users"]);
    if(isset($_SESSION["reg"]))
        unset($_SESSION['reg']);
  ?>        
      </div>
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->
  <div class="rightpanel">
  </div><!-- rightpanel -->
</section>

<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jquery-ui-1.10.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/modernizr.min.js"></script>
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/toggles.min.js"></script>
<script src="js/retina.min.js"></script>
<script src="js/jquery.cookies.js"></script>
<script src="js/flot/jquery.flot.min.js"></script>
<script src="js/flot/jquery.flot.resize.min.js"></script>
<script src="js/flot/jquery.flot.spline.min.js"></script>
<script src="js/morris.min.js"></script>
<script src="js/raphael-2.1.0.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/dashboard.js"></script>

</body>
</html>