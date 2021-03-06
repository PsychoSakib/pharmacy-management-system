<?php 
ob_start();
session_start();
if($_SESSION['name']!='www.somrat.info')
{
  header('location: index.php');
}
include("header.php"); 
include("../connection.php");
include ("left-sidebar-set.php") ; 
require_once('delete_confirm.php');


if(isset($_POST['form1']))
{
  try {
    
    if(empty($_POST['doc_name'])) {
      throw new Exception("Doctor Name can not be empty.");
    }
    
    $statement = $db->prepare("SELECT * FROM doctors WHERE doc_name=?");
    $statement->execute(array($_POST['doc_name']));
    $total = $statement->rowCount();
    
    if($total>0) {
      throw new Exception("Doctor Name already exists.");
    }

    
    $statement = $db->prepare("INSERT INTO doctors (doc_name) VALUES (?)");
    $statement->execute(array($_POST['doc_name']));
    
    $success_message = "Doctor Name has been inserted successfully.";
    
  
  }
  
  catch(Exception $e) { 
    $error_message = $e->getMessage();
  }
} 



if(isset($_REQUEST['id'])) 
{
  $id = $_REQUEST['id'];
  
  $statement = $db->prepare("DELETE FROM doctors WHERE doc_id=?");
  $statement->execute(array($id));
  
  $success_message2 = "Doctor Name has been deleted successfully.";
  
}

?>

<?php

if(isset($_POST['form_edit_Doctor']))
{
  $id = $_REQUEST['cid'];
  try {
    
    if(empty($_POST['doc_name'])) {
      throw new Exception("Doctor Name can not be empty.");
    }
    
    
    $statement = $db->prepare("UPDATE doctors SET doc_name = ? WHERE doc_id = ? ");
    $statement->execute(array($_POST['doc_name'],$id));
    
    $success_message = "Doctor Name has been updated successfully.";
    
  
  }
  
  catch(Exception $e) { 
    $error_message = $e->getMessage();
  }
} 

?>

<div class="content-wrapper">
  <section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
      <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Add New Doctor</h3>
          </div><!-- /.box-header -->
            <?php
              if(isset($error_message))
              { ?>
                <div class="alert alert-danger">
                    <p class=""><?php echo $error_message ; ?></p>
                </div>
            <?php 
               } 
             else if(isset($success_message))
              { ?>
                <div class="alert alert-success">
                    <p class=""><?php echo $success_message ; ?></p>
                </div>
            <?php } ?>
          
          <!-- form start -->
          <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="box-body">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-3 control-label">Doctor Name </label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="inputEmail3" placeholder="Insert Product Name" name="doc_name">
                </div>
              </div>

            </div><!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" class="btn btn-info pull-right" name="form1">SUBMIT</button>
            </div><!-- /.box-footer -->
          </form>

          

        </div><!-- /.box -->
    </div>

    <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">View All Doctor </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">  
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>

                    <tbody>

          <?php
        $i=0;
        $statement = $db->prepare("SELECT * FROM doctors ORDER BY doc_name ASC");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row)
        {
          $i++;
          ?>

            <tr>
              <td><?php echo $i ; ?></td>
                  <td><?php echo $row['doc_name']; ?></td>
                  

                  <td><button class="btn btn-primary" data-toggle="modal" data-target="#editModal<?php echo $i ; ?>">Edit</button></td>
                  <!--product edit modal -->
                  <div class="modal fade" id="editModal<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                                  <h5 class="modal-title">Update Infromation</h5>
                              </div>

                              <div class="modal-body">
                                  <!-- The form is placed inside the body of modal -->      
                            <div class="box-header with-border">
                              <h3 class="box-title">Edit Doctor Name </h3>
                            </div><!-- /.box-header -->
                            
                            <!-- form start -->
                            <form class="form-horizontal" action="Doctor.php?cid=<?php echo $row['doc_id']; ?>" method="post" enctype="multipart/form-data">
                              <div class="box-body">
                                <div class="form-group">
                                  <label for="inputEmail3" class="col-sm-4 control-label">Doctor Name</label>
                                  <div class="col-sm-6">
                                    <input type="text" class="form-control" id="inputEmail3" value="<?php echo $row['doc_name']; ?>" name="doc_name">
                                  </div>
                                </div>

                              </div><!-- /.box-body -->
                              <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" name="form_edit_Doctor">UPDATE</button>
                              </div><!-- /.box-footer -->
                            </form>

                              </div>
                          </div>
                      </div>
                  </div>
                  <!--Product edit modal end -->


                  <td><form method="POST" action="Doctor.php?id=<?php echo $row['doc_id']; ?>" accept-charset="UTF-8" style="display:inline"><button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Delete Doctor" data-message="Are you sure you want to delete ?"> <i class="glyphicon glyphicon-trash"></i> Delete</button></form></td>

                 

              </tr>
              
          <?php

            }

          ?>
        
              
          </tbody>
                    
        <tfoot>
           <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </tfoot>
      </table>
    </div><!-- /.box-body -->
    </div>
  </div><!-- /.box -->
</div>
</div>



  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<?php include ("footer.php"); ?>