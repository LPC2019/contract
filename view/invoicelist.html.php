<?php
/**
 * The html template file of all method of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?> 
<?php include '../../common/view/sortable.html.php';?>
<!--<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php //echo $line ? zget($lines, $line) : $lang->product->line;?>
      <?php //if($line) echo html::a(inlink('all', "productID={$productID}&line=&status={$status}"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");?>
    </div>
  </div>-->
  <div class="btn-toolbar pull-left"> <!-- Third bar, left hand side buttons-->
    <?php 
    /*foreach($lang->product->featureBar['all'] as $key => $label)
    {
        if(is_string($label)) $link = inlink("all", "productID={$productID}&line=&status={$key}");
        if(is_array($label))
        {
            $link  = zget($label, 'link', '');
            $label = zget($label, 'label', ''); 
            if(!$link or !$label) continue;
        }
        $label   = "<span class='text'>{$label}</span>";
        $label  .= $key == $status ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
        $active  = $key == $status ? 'btn-active-text' : '';
        echo html::a($link, $label, '', "class='btn btn-link {$active}' id='{$key}'");
    }*/
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('product', 'export', "status=$status&orderBy=$orderBy", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export'")?>
    <?php common::printLink('contract', 'createinvoice', "contractID=$contract->id", "<i class='icon-plus'></i> " . $lang->invoice->create, '', "class='btn btn-primary'") ?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <!--<div class="side-col" id="sidebar"> 
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php //echo $lineTree;?> 
      <div class="text-center">
        <?php //common::printLink('tree', 'browse', "rootID=$productID&view=line", $lang->tree->manageLine, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div> commented in 2022.1.10-->
  </div>
  <div class="main-col">
    <form class="main-table table-product" data-ride="table" method="post" id='productsForm' action='<?php echo inLink('batchEdit', "productID=$productID");?>'>
      <?php $canOrder = (common::hasPriv('product', 'updateOrder'))?>
      <?php $canBatchEdit = common::hasPriv('product', 'batchEdit'); ?>
      <table class="table has-sort-head table-fixed" id='productList'>
        <?php $vars = "productID=$productID&line=$line&status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <thead>
          <tr>
            <th class='c-id'>
              <?php //if($canBatchEdit):?>
              <!--<div class="checkbox-primary check-all" title="<?php //echo $lang->selectAll?>">-->
                <label></label> 
              </div>
              <?php //endif;?>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->contract->name);?></th>
            <th class='w-110px text-left'><?php //common::printOrderLink('line', $orderBy, $vars, $lang->product->line);?></th>
            <th class='w-130px' title='<?php echo $lang->invoice->eoRef;?>'><?php echo $lang->invoice->eoRef;?></th>
            <th class='w-80px' title='<?php echo $lang->invoice->status;?>'><?php echo $lang->invoice->status;?></th>
            <th class='w-100px' title='<?php echo $lang->invoice->amount;?>'><?php echo $lang->invoice->amount;?></th>
            <th class='w-110px' title='<?php echo $lang->invoice->submitteddate;?>'><?php echo $lang->invoice->submitteddate;?></th>
            <th class='w-100px' title='<?php echo $lang->invoice->step;?>'><?php echo $lang->invoice->step;?></th>
            <th class='w-100px' title='<?php echo $lang->invoice->action;?>'><?php echo $lang->invoice->action;?></th>
            <?php if($canOrder):?>
            <!--<th class='w-70px sort-default'><?php common::printOrderLink('order', $orderBy, $vars, $lang->product->updateOrder);?></th>-->
            <?php endif;?>
          </tr>
        </thead>
        <tbody class="sortable" id="productTableList">
        <?php //foreach($productStats as $product):
              foreach($invoiceStats as $invoice):?>
        <tr data-id='<?php echo $invoice->id ?>' data-order='<?php echo $invoice->id;?>'>
          <td class='c-id'>
            <?php //if($canBatchEdit):?>
            <?php //echo html::checkbox('productIDList', array($product->id => sprintf('%03d', $product->id)));?>
            <?php //else:?>
            <a href='<?php echo $this->createLink('contract', 'invoiceview', "invoice=" . $invoice->id);?>'><?php printf('%03d', $invoice->id)?></a>
            <?php //endif;?>
          </td>
          <td class="c-name" title='<?php echo $invoice->contractName?>'><?php echo html::a($this->createLink('contract', 'invoiceview', "invoice=" . $invoice->id), $contract->contractName);?></td> <!-- changed the hyperlink to invoiceview 2020.1.10-->
          <!--<td title='<?php //echo zget($lines, $product->line, '')?>'><?php// echo zget($lines, $product->line, '');?></td>-->
          <td class='text-center'><?php //echo $product->stories['active'];?></td>
          <td class='text-center'><?php echo $invoice->refNo;?></td>
          <td class='text-center'><?php echo $invoice->status;?></td>
          <td class='text-center'><?php echo $invoice->amount;?></td>
          <td class='text-center'><?php echo $invoice->submitdate?></td>
          <td class='text-center'><?php echo $invoice->step;?></td>
          <td>

            <?php if($invoice->status=='pending')
                {
                  common::printIcon('contract', 'submit', "invoiceID=$invoice->id", $id, 'list','','','iframe',true);

                  common::printIcon('contract', 'editinvoice', "invoiceID=$invoice->id", $id, 'list');
            }?></td>
          <?php if($canOrder):?>
          ,<!--<td class='c-actions sort-handler'><i class="icon icon-move"></i></td>-->
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($productStats):?>
      <div class="table-footer">
        <?php //if($canBatchEdit):?>
        <!--<div class="checkbox-primary check-all"><label><?php //echo $lang->selectAll?></label></div>-->
        <div class="table-actions btn-toolbar">
          <?php //echo html::submitButton($lang->edit, '', 'btn');?>
        </div>
        <?php //endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>