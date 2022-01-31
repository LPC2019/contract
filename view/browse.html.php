<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datatable.fix.html.php';?>
<?php js::set('browseType', $browseType);?>
<?php js::set('productID', $productID);?>
<?php js::set('branch', $branch);?>
<?php
/* Set unfold parent taskID. */
$this->app->loadLang('project');


?>
<div id="mainMenu" class="clearfix">
  

  <div class="btn-toolbar pull-left">
   
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printIcon('product', 'report', "productID=$productID&browseType=$browseType&branchID=$branch&moduleID=$moduleID&chartType=pie&storyType=$storyType", '', 'button', 'bar-chart muted'); ?>
    <div class="btn-group">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu" id='exportActionMenu'>
        <?php
        $class = common::hasPriv('contract', 'export') ? '' : "class=disabled";
        $misc  = common::hasPriv('contract', 'export') ? "class='export'" : "class=disabled";
        $link  = common::hasPriv('contract', 'export') ?  $this->createLink('contract', 'export', "productID=$productID&orderBy=$orderBy&projectID=0&browseType=$browseType") : '#';
        echo "<li $class>" . html::a($link, $lang->contract->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php if(!common::checkNotCN()):?>
    <?php if(common::hasPriv('contract', 'batchCreate')) echo html::a($this->createLink('contract', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID"), "<i class='icon icon-plus'></i> {$lang->story->batchCreate}", '', "class='btn btn btn-secondary'");?>
    <?php

        $link = $this->createLink('contract', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID");
        if(common::hasPriv('contract', 'create')) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->contract->create}", '', "class='btn btn-primary'");
    
    ?>
    <?php else:?>
    <div class='btn-group dropdown-hover'>
      <?php

          $link     = $this->createLink('contract', 'create', "product=$productID&branch=$branch&moduleID=$moduleID");
          $disabled = '';
          if(!common::hasPriv('contract', 'create'))
          {
              $link     = '###';
              $disabled = 'disabled';
          }
          echo html::a($link, "<i class='icon icon-plus'></i> {$lang->contract->create} </span><span class='caret'>", '', "class='btn btn-primary $disabled'");
      
      ?>
      <ul class='dropdown-menu'>
        <?php $disabled = common::hasPriv('contract', 'batchCreate') ? '' : "class='disabled'";?>
        <li <?php echo $disabled?>>
        <?php
          $batchLink = $this->createLink('contract', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID");
          echo "<li>" . html::a($batchLink, "<i class='icon icon-plus'></i>" . $lang->story->batchCreate) . "</li>";
        ?>
        </li>
      </ul>
    </div>
    <?php endif;?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='story'></div>
    <?php if(empty($contracts)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->story->noStory;?></span>
        <?php if(common::hasPriv('contract', 'create')):?>
        <?php echo html::a($this->createLink('contract', 'create', "productID={$productID}&branch={$branch}&moduleID={$moduleID}"), "<i class='icon icon-plus'></i> " . $lang->story->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class="main-table table-story skip-iframe-modal" method="post" id='productStoryForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
      $vars         = "productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";

      if($useDatatable) include '../../common/view/datatable.html.php';
      $newSetting = $this->datatable->getSetting('product');
      $setting[0]=$newSetting[0];
      $setting[1]=$newSetting[2];
      $setting[2]=clone $newSetting[2];

      $setting[2]->order=4;
      $setting[2]->id="RefNo";
      $setting[2]->title="Ref.No.";
      $setting[2]->width="120";
      $setting[3]=$newSetting[7];
      $setting[4]=$newSetting[10];
      
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      $columns = 0;

      $canBatchEdit         = common::hasPriv('contract', 'batchEdit');
      $canBatchClose        = common::hasPriv('contract', 'batchClose') and strtolower($browseType) != 'closedbyme' and strtolower($browseType) != 'closedstory';
      $canBatchReview       = common::hasPriv('contract', 'batchReview');
      $canBatchChangeStage  = common::hasPriv('contract', 'batchChangeStage');
      $canBatchChangeBranch = common::hasPriv('contract', 'batchChangeBranch');
      $canBatchChangeModule = common::hasPriv('contract', 'batchChangeModule');
      $canBatchChangePlan   = common::hasPriv('contract', 'batchChangePlan');
      $canBatchAssignTo     = common::hasPriv('contract', 'batchAssignTo');
      $canBatchAction       = false;//($canBatchEdit or $canBatchClose or $canBatchReview or $canBatchChangeStage or $canBatchChangeModule or $canBatchChangePlan or $canBatchAssignTo);
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='storyList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
        <thead>
          <tr>
          <?php
          foreach($setting as $key => $value)
          {
              if($value->show)
              {
                  $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
                  $columns ++;
              }
          }
          ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($contracts as $contract):?>
            
          <tr data-id='<?php echo $contract->id?>' data-estimate='<?php echo $story->estimate?>' data-cases='<?php echo zget($storyCases, $contract->id, 0);?>'>
            <?php foreach($setting as $key => $value){
             $this->contract->printCell($value, $contract, $users);
            } 
             ?>
              
            </tr>

          <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!$useDatatable) echo '</div>';?>
      <div class="table-footer">
        <?php if($canBatchAction):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class="table-actions btn-toolbar">
          <div class='btn-group dropup'>
            <?php
            $disabled   = $canBatchEdit ? '' : "disabled='disabled'";
            $actionLink = $this->createLink('contract', 'batchEdit', "productID=$productID&projectID=0&branch=$branch");
            ?>
            <?php echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu'>
              <?php
              $class         = $canBatchClose ? '' : "class='disabled'";
              $actionLink    = $this->createLink('story', 'batchClose', "productID=$productID&projectID=0");
              $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink')\"" : '';
              echo "<li $class>" . html::a('#', $lang->close, '', $misc) . "</li>";



             

              if($canBatchChangeStage)
              {
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->story->stageAB, '', "id='stageItem'");
                  echo "<ul class='dropdown-menu'>";
                  foreach($lang->story->stageList as $key => $stage)
                  {
                      if(empty($key)) continue;
                      if(strpos('tested|verified|released|closed', $key) === false) continue;
                      $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                      echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"") . "</li>";
                  }
                  echo '</ul></li>';
              }
              else
              {
                  $class= "class='disabled'";
                  echo "<li $class>" . html::a('javascript:;', $lang->story->stageAB, '', $class) . '</li>';
              }
              ?>
            </ul>
          </div>

       
  

        </div>
        <div class="table-statistic"><?php echo $summary;?></div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<script>
var moduleID = <?php echo $moduleID?>;
var branchID = $.cookie('storyBranch');
$('#module<?php echo $moduleID;?>').closest('li').addClass('active');
$('#branch' + branchID).closest('li').addClass('active');

$(function()
{
    // Update table summary text
    <?php
    $storyCommon = $lang->storyCommon;
    if(!empty($config->URAndSR))
    {
        if($storyType == 'requirement') $storyCommon = $lang->URCommon;
        if($storyType == 'story') $storyCommon = $lang->SRCommon;
    }
    ?>
    var checkedSummary = '<?php echo str_replace('%storyCommon%', $storyCommon, $lang->product->checkedSummary)?>';
    $('#productStoryForm').table(
    {
        statisticCreator: function(table)
        {
            var $checkedRows = table.getTable().find(table.isDataTable ? '.datatable-row-left.checked' : 'tbody>tr.checked');
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var checkedTotal = $checkedRows.length;
            if(!checkedTotal) return;

            var checkedEstimate = 0;
            var checkedCase     = 0;
            $checkedRows.each(function()
            {
                var $row = $(this);
                if ($originTable)
                {
                    $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');
                }
                var data = $row.data();
                checkedEstimate += data.estimate;
                if(data.cases > 0) checkedCase += 1;
            });
            var rate = Math.round(checkedCase / checkedTotal * 10000 / 100) + '' + '%';
            return checkedSummary.replace('%total%', checkedTotal)
                  .replace('%estimate%', checkedEstimate.toFixed(1))
                  .replace('%rate%', rate);
        }
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
