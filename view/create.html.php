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
      <h2><?php echo $lang->contract->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="createForm" method="post" target='hiddenwin'>
      <table class="table table-form">
        <tbody>
        <tr>
            <th class='w-140px'><?php echo "Asset";?></th>
            <td><?php echo html::select('assetID', $products,$rootID, "class='form-control' required");?></td><td></td>
          </tr>
          <tr>
            <th class='w-140px'><?php echo $lang->contract->name;?></th>
            <td><?php echo html::input('contractName', '', "class='form-control input-product-title' required");?></td><td></td>
          </tr>
          <tr>
          <tr>
            <th class='w-140px'><?php echo $lang->contract->eoRef;?></th>
            <td><?php echo html::input('refNo', '', "class='form-control input-product-title' required");?></td><td></td>
          </tr>
          <tr>
          <th><?php echo $lang->contract->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', (isset($plan) && !empty($plan->begin) ? $plan->begin : date('Y-m-d')), "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo "to";?></span>
              <?php echo html::input('end', (isset($plan) && !empty($plan->end) ? $plan->end : ''), "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->project->end . "' required");?>
            </div>
          </td>
        </tr>
          <tr>
            <th class='w-140px'><?php echo $lang->contract->amount." (HKD)";?></th>
            <td><input name='amount'id='amount' type="number" min="1" step=".01" class='form-control input-product-title' required></td>
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
            <th><?php echo "Contract Manager";?></th>
            <td><?php echo html::select('contractManager', $poUsers, $this->app->user->account, "class='form-control chosen'");?></td><td></td>
          </tr>
          <tr>
            <th><?php echo "Appointed Party";?></th>
            <td><?php echo html::select('appointedParty', $poUsers, $this->app->user->account, "class='form-control chosen'");?></td><td></td>
          </tr>

          <tr>
            <th style="vertical-align:top"><?php echo "Approval List";?></th>
            <td colspan='2'>
            <table style="width:50%;">

            <?php
            $numArray['1']='1';
            $numArray['2']='2';
            $numArray['3']='3';
            $numArray['4']='4';
            $numArray['5']='5';
            $numArray['6']='6';
            $itemRow = "
              <tr >
              <td>".html::select('ap[]', $poUsers, '', "class='form-control chosen'")."</td>
              <td>".html::select('order[]',$numArray, 1,'class="form-control chosen"')."</td>
              <td><input type='checkbox' id='sign[]' name='sign[]' value='true'>
              <td class='c-actions'>
                <a href='javascript:void(0)' class='btn btn-link' onclick='addItem(this)'><i class='icon-plus'></i></a>
                <a href='javascript:void(0)' class='btn btn-link' onclick='delItem(this)'><i class='icon-close'></i></a>
              </td>
              </tr>
              ";
            ?>
            <?php js::set('itemRow',$itemRow )?>
            <script>
		       temp=0;
            	      function addItem(clickedButton)
                      {
			  
                        $(clickedButton).parent().parent().after(itemRow);
			// console.log($(clickedButton).parent().parent().parent());
			//temp=$(clickedButton).parent().parent();
                      }

                      function delItem(clickedButton)
                      {
                          $(clickedButton).parent().parent().remove();
                      }

                      $(function()
                      {
                         $('#' + module + 'Tab').addClass('btn-active-text');
                          $('#' + field + 'Tab').addClass('active');
                      }
			)

            </script>
                  <thead>
                    <tr>
                      <th class="w-300px">Approver</th><th class="w-120px">Sequence</th><th class="w-100px">Need Sign?</th><th class="w-60px">add/delete</th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php echo $itemRow?>
                        </tr>
                  </tbody>
            </table>

            </td>
          </tr>  
            <th><?php echo $lang->contract->desc;?></th>
            <td colspan='2'>
              <!-- <?php echo $this->fetch('user', 'ajaxPrintTemplates', "type=task&link=desc");?>-->
              <?php echo html::textarea('description', '', "rows='8' class='form-control kindeditor' hidefocus='true' tabindex=''");?>
            </td>
          </tr>  
          <!--
          <tr>
            <th><?php echo $lang->contract->acl;?></th>
            <td colspan='2'><?php echo nl2br(html::radio('acl', $lang->contract->aclList, 'open', "onclick='setWhite(this.value);'", 'block'));?></td>
          </tr>  
          <tr id='whitelistBox' class='hidden'>
            <th><?php echo $lang->contract->whitelist;?></th>
            <td colspan='2'><?php echo html::checkbox('whitelist', $groups, '', '', 'inline');?></td>
          </tr>
                    -->  
          <tr>
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
