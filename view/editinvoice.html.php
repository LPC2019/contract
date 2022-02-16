<?php
/**
 * The create view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: create.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.pm
 */
?>


<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->invoice->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="createForm" method="post" target='hiddenwin'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-140px'><?php echo $lang->contract->common;?></th>
            <td><?php echo html::select('contractID', array($contract->id => $contract->contractName), $contract->id, "class='form-control ' disabled");?></td><td></td>
          </tr>
          <tr>
          <tr>
            <th class='w-140px'><?php echo $lang->contract->eoRef;?></th>
            <td><?php echo html::input('refNo', $invoice->refNo, "class='form-control input-product-title' required");?></td><td></td>
          </tr>
         
          <tr>
            <th class='w-140px'><?php echo $lang->invoice->amount." (HKD)";?></th>
            <td><input name='amount'id='amount'  type="number" min="1" step=".01" value=<?php echo $invoice->amount?> class='form-control input-product-title' required></td>
            <td></td>
            <script type="text/javascript">
              $(document).ready(function () {
                  $("#amount").change(function() {
                      $(this).val(parseFloat($(this).val()).toFixed(2));
                  });
              });
          </script>
          </tr>    

          <tr>
            <th style="vertical-align:top"><?php echo "Invoice Details";?></th>
            <td colspan='2'>
            <table style='width:100%'>
            <?php
            $itemRow = "
              <tr >
              <td><input name='item[]'id='item[]' type='text' class='form-control' ></td>
              <td><input name='price[]'id='price[]' type='number' min='1' step='0.1' class='form-control' ></td>
              <td class='c-actions'>
                <a href='javascript:void(0)' class='btn btn-link' onclick='addItem(this)'><i class='icon-plus'></i></a>
                <a href='javascript:void(0)' class='btn btn-link' onclick='delItem(this)'><i class='icon-close'></i></a>
              </td>
              </tr>";
            ?>
            <?php js::set('itemRow',$itemRow )?>
            <script>
            function addItem(clickedButton)
                      {
                          $(clickedButton).parent().parent().after(itemRow);
                      }

                      function delItem(clickedButton)
                      {
                          $(clickedButton).parent().parent().remove();
                      }

                      $(function()
                      {
                          $('#' + module + 'Tab').addClass('btn-active-text');
                          $('#' + field + 'Tab').addClass('active');
                      })

            </script>
                  <thead>
                    <tr>
                      <th class="w-300px">Item</th><th class="w-120px">Item Amount</th><th class="w-60px">Add/Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php 
                        if(!empty($invoicedetails)){
                          foreach( $invoicedetails as $key){
                            echo "<tr >
                            <td><input name='item[]'id='item[]' value='$key->item' type='text' class='form-control' ></td>
                            <td><input name='price[]'id='price[]' type='number' value='$key->price' min='1' step='0.1' class='form-control' ></td>
                            <td class='c-actions'>
                              <a href='javascript:void(0)' class='btn btn-link' onclick='addItem(this)'><i class='icon-plus'></i></a>
                              <a href='javascript:void(0)' class='btn btn-link' onclick='delItem(this)'><i class='icon-close'></i></a>
                            </td>
                            </tr>";
                          }
                        }else{
                          echo $itemRow;
                        }
                        ?>
                        </tr>
                  </tbody>
            </table>

            </td>
          </tr>  
            <th><?php echo $lang->contract->desc;?></th>
            <td colspan='2'>
              <!-- <?php echo $this->fetch('user', 'ajaxPrintTemplates', "type=task&link=desc");?>-->
              <?php echo html::textarea('description', $invoice->description, "rows='8' class='form-control kindeditor' hidefocus='true' tabindex=''");?>
            </td>
          </tr>
          <tr id='fileBox'>
            <th><?php echo "Invoice Softcopy";?></th>
            <td colspan='2'><input  class='file' type="file" name="files[]" onchange="checkDangerExtension(this)"><span style="color:red">* only accept 1 files pdf format </span></br><span style="color:red"> it will overwrite origenal one</span></td>
          </tr>  
          
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
