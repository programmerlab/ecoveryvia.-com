<?php $curPage='homepage';
$curSubPage='view_homepage'; 
include("includes/header.php");

/* ******** start delete record ************** */
if ((isset($_GET['delid']) && $_GET['delid'] != ""))
{	
	$dataDel = $obj->getRowFromAnyTable(TBL_CLIENT_CASE,'id',$_GET['delid']);
	@unlink('images/uploadUserImages/'.$dataDel['image']);
	$obj->deleteRowFromAnyTable(TBL_CLIENT_CASE,'id',$_GET['delid']);
	 
	$obj->setMessageForNextPage('Record deleted successfully','MESSAGE_GREEN');
	header('location:'.REQUEST_URL);
} 
/* ******** end delete record ************** */

/* ******** start multi delete record ************** */
if ((isset($_POST['sub_multiform']) && $_POST['sub_multiform'] != "" && $_POST['sub_multiform'] == "delete"))
{
	for($di=0;$di<count($_POST['checkbox']);$di++)
	{
		$dataDel = $obj->getRowFromAnyTable(TBL_CLIENT_CASE,'id',$_POST['checkbox'][$di]);
		@unlink('images/uploadUserImages/'.$dataDel['image']);
		$obj->deleteRowFromAnyTable(TBL_CLIENT_CASE,'id',$_POST['checkbox'][$di]);
		$obj->deleteRowFromAnyTable(TBL_CLIENT_CASE,'id',$_POST['checkbox'][$di]);
	}
	$obj->setMessageForNextPage('Record deleted successfully','MESSAGE_GREEN');
	header('location:'.REQUEST_URL);
} 
/* ******** end multi delete record ************** */

/* ******** start change status ************** */
if ((isset($_GET['statusid']) && $_GET['statusid'] != "") && (isset($_GET['status']) && $_GET['status'] != ""))
{	
	$update= ($_GET['status'] == 1) ? 2 : 1;
	$obj->updateRowFromAnyTable(TBL_CLIENT_CASE,'status="'.$update.'"','id="'.$_GET['statusid'].'"');
	 
	$obj->setMessageForNextPage('Status changed successfully','MESSAGE_GREEN');
	header('location:'.REQUEST_URL);
}
/* ******** end change status ************** */ 

/* ******** start multi change status ************** */
if((isset($_POST['sub_multiform']) && $_POST['sub_multiform'] != "" && $_POST['sub_multiform'] == "status"))
{
	for($di=0;$di<count($_POST['checkbox']);$di++)
	{
		$dataStatus = $obj->getRowFromAnyTable(TBL_CLIENT_CASE,'id',$_POST['checkbox'][$di]);
		$update= ($dataStatus['status'] == 1) ? 2 : 1;
		$obj->updateRowFromAnyTable(TBL_CLIENT_CASE,'status="'.$update.'"','id="'.$_POST['checkbox'][$di].'"');
		$dataStatusUser = $obj->getRowFromAnyTable(TBL_CLIENT_CASE,'id',$_POST['checkbox'][$di]);
		$update= ($dataStatusUser['status'] == 1) ? 2 : 1;
	 
	}
	$obj->setMessageForNextPage('Status changed successfully','MESSAGE_GREEN');
	header('location:'.REQUEST_URL);
}
/* ******** end multi change status************** */




$recordPerPage = 5;
if(isset($_GET['search']) && $_GET['search']!='' && $_GET['search']!='Search')
{
 	$sql="SELECT  * from client_records  WHERE parties_name LIKE '%".$_GET['search']."%' OR  appellant LIKE '%".$_GET['search']."%' OR  file_no LIKE '%".$_GET['search']."%' OR  court LIKE '%".$_GET['search']."%'";
 
} 
else
{
	$sql="SELECT  * from client_records  order by id DESC";
}
include('includes/pagination.php');// FOR PAGINATION $sql SHOULD BE SAME AS $sql
$query = $obj->executeQry($sql,$link);
$numRows=$obj->getRow($query);
?>
<!-- start content-outer ........................................................................................................................START -->

<div id="content-outer">
  <!-- start content -->
  <div id="content">
    <!--  start page-heading -->
    <div id="page-heading">
      <h1>Manage Client Case</h1>
    </div>
    <!-- end page-heading -->
    <!--  start top-search -->
    <div class="search-box">
      <table align="right" width="" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><form id="searchFrm" name="searchFrm" method="get" action="">
              <input type="text" name="search" value="Search" onblur="if (this.value=='') { this.value='Search'; }" onfocus="if (this.value=='Search') { this.value=''; }" class="top-search-inp" />
            </form></td>
          <td><input type="image" onclick="$('#searchFrm').submit();" src="images/shared/top_search_btn.gif"></td>
        </tr>
      </table>
    </div>
    <!--  end top-search -->
    <table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
      <tr>
        <th rowspan="3" class="sized"><img src="images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
        <th class="topleft"></th>
        <td id="tbl-border-top">&nbsp;</td>
        <th class="topright"></th>
        <th rowspan="3" class="sized"><img src="images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
      </tr>
      <tr>
        <td id="tbl-border-left"></td>
        <td><!--  start content-table-inner ...................................................................... START -->
          <div id="content-table-inner">
            <?php
           if($numRows > 0)
		   {
           ?>
            <!--  start table-content  -->
            <div id="table-content">
              <?php include('includes/message.php');?>
              <!--  start product-table ..................................................................................... -->
              <form id="mainform" name="mainform" action="" method="post">
                <table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
                  <thead>
                    <tr>
                      <th class="table-header-check"><a id="toggle-all" ></a> </th>
                     
                      <th class="table-header-repeat line-left minwidth-1"><a onclick="SortTable(2);" href="javascript:;">Parties Name</a></th>
                      <th class="table-header-repeat line-left minwidth-1"><a onclick="SortTable(3);" href="javascript:;">Court</a></th>   <th class="table-header-repeat line-left">Address</th>
                      <th class="table-header-repeat line-left"><a onclick="SortTable(4);" href="javascript:;">Blance</a></th>
                      <th class="table-header-repeat line-left">Result</th>
                       <th class="table-header-repeat line-left">Remark</th>
                      <th class="table-header-repeat line-left">Options</th>
                    </tr>
                  </thead>
                  <?php
				  $i = 1;
                  while($data = $obj->fetchRow($query))
				  {
				  ?>
                  <tr <?php if($i%2==0){echo 'class="alternate-row"';}?>>
                    <td><input name="checkbox[]" id="checkbox<?php echo $i;?>" value="<?php echo $data['id'];?>"  type="checkbox"/></td>
                    <td><?php echo $data['parties_name'];?></td>
                    <td><?php echo $data['court'];?></td>
                    <td><?php echo $data['address'];?></td>
                    <td><?php echo $data['blance'];?></td>
                    <td><?php echo $data['result'];?></td>
                    <td><?php echo $data['remark'];?></td>
                    <td class="options-width">
                    <a href="add-edit-user.php<?php echo DEF_QRY_STR;?>&editid=<?php echo $data['id'];?>&pagename=1" title="Edit" class="icon-1 info-tooltip">
					 <a href="view-user.php<?php echo DEF_QRY_STR;?>&editid=<?php echo $data['id'];?>&pagename=1" title="View Details" class="icon-1 info-tooltip">
				 </a>  <a href="javascript:confirmDelete('<?php echo REQUEST_URL;?>&delid=<?php echo $data['id'];?>')" title="Delete" class="icon-4 info-tooltip"></a>
                      <?php
                        if($data['status']=='1')
						{ 
						?>
                      <a href="<?php echo REQUEST_URL;?>&statusid=<?php echo $data['id'];?>&status=<?php echo $data['status'];?>" title="Status" class="icon-2 info-tooltip"></a>
                       <span style="color:#060; margin:7px;">  <?php echo " "; ?> </span>
                      <?php
						}
						else
						{
						?>
                      <a href="<?php echo REQUEST_URL;?>&statusid=<?php echo $data['id'];?>&status=<?php echo $data['status'];?>" title="Status" class="icon-5 info-tooltip"></a>
                     <span style="color:#F00; margin:15px;">  <?php echo " "; ?> </span>
                      </td>
                    <?php
						} 
						?>
                  </tr>
                  <?php
				  $i++;}
				  ?>
                </table>
                <!--  end product-table................................... -->
                <input type="hidden" id="multiType" value="" name="sub_multiform">
              </form>
            </div>
            <!--  end content-table  -->
            <!--  start paging..................................................... -->
            <table border="0" cellpadding="0" cellspacing="0" id="paging-table" width="100%" align="center">
              <tr>
                <td align="left"><div id="actions-box"> <a href="javascript:void(0);" class="action-slider"></a>
                    <div id="actions-box-slider">
                 <!----------   <a onClick="javascript:return check_all('status');" href="javascript:void(0);" class="action-edit">Status</a> 
                    --><a onClick="javascript:return check_all('delete');" href="javascript:void(0);" class="action-delete">Delete</a> </div>
                    <div class="clear"></div>
                  </div></td>
                <td align="right" style="float:right;"><?php include('includes/paginationLink.php');?></td>
                <td valign="top" align="right" width="140" ><?php include('includes/recordPerpPage.php');?></td>
              </tr>
            </table>
            <!--  end paging................ -->
            <div class="clear"></div>
            <?php
		   }
		   else
		   {
		   ?>
            <div id="table-content">
              <?php $MESSAGE_YELLOW[] = 'No record found...';include('includes/message.php');?>
            </div>
            <?php
		   }
		   ?>
          </div>
          <!--  end content-table-inner ............................................END  --></td>
        <td id="tbl-border-right"></td>
      </tr>
      <tr>
        <th class="sized bottomleft"></th>
        <td id="tbl-border-bottom">&nbsp;</td>
        <th class="sized bottomright"></th>
      </tr>
    </table>
    <div class="clear">&nbsp;</div>
  </div>
  <!--  end content -->
  <div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->
<?php include("includes/footer.php");?>