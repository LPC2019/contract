<?php
/**
 * The view view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: view.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<style type="text/css">
table {
    border-collapse: collapse;
    border-spacing: 0;
	width:25%;
	position:relative;
}

.pure-table {
    border: 1px solid #cbcbcb;
	width:50%；
}
  
.pure-table td,.pure-table th {
    padding: .5em 1em;
}
 
.pure-table thead {
    background-color: #e0e0e0;
    color: #000;
    text-align: left;
    vertical-align: bottom;
}
 
.pure-table-horizontal td,.pure-table-horizontal th {
    border-width: 0 0 1px 0;
    border-bottom: 1px solid #cbcbcb;
}
 
</style>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class="main-row">
<div class="col-4 side-col">
    <div class="row">
      <div class="col-sm-12">
        <div class="cell">
          <div class="detail">
            <h2 class="detail-title"><span class="label-id"><?php echo $contract->id;?></span> <span class="label label-light label-outline"><?php echo $contract->refNo;?></span> <?php echo $contract->contractName;?></h2>
            <div class="detail-content article-content">
              <p><span class="text-limit" data-limit-size="40"><?php echo $contract->desc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></p>
              <p>
                <span class="label label-success label-outline"><?php echo $lang->product->status . ':' . $this->processStatus('product', $product);?></span>

                <?php if($contract->deleted):?>
                <span class='label label-danger label-outline'><?php echo $lang->contract->deleted;?></span>
                <?php endif; ?>
              </p>
            </div>
          </div>
          <div class="detail">
              <div class="detail-title"><strong><?php echo "Related Parties";?></strong></div>
            <div class="detail-content">
              <table class="table table-data">
                <tbody>
                  <tr>
                    <th class='w-150px'><i class="icon icon-person icon-sm"></i> <?php echo "Asset Owner";?></th>
                    <td><em><?php echo zget($users, $product->PO);?></em></td>
                    </tr>
                  <tr>

                    <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->contract->contractManager;?></th>
                    <td><em><?php echo zget($users, $contract->contractManager);?></em></td>
                  </tr>
                  <tr>
                    <th><i class="icon icon-person icon-sm"></i> <?php echo $lang->contract->appointedParty;?></th>
                    <td><em><?php echo zget($users, $contract->appointedParty);?></em></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="detail">
            <div class="detail-title"><strong><?php echo $lang->product->basicInfo;?></strong></div>
            <div class="detail-content">
              <table class="table table-data data-basic">
                <tbody>
                  <tr>
                    <th><?php echo $lang->contract->openedBy?></th>
                    <td><em><?php echo zget($users, $contract->createdBy);?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->contract->createdDate?></th>
                    <td><em><?php echo formatTime($contract->createdDate, DT_DATE1);?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->contract->amount?></th>
                    <td><em><?php echo $contract->amount;?></em></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->contract->dateRange?></th>
                    <td><em><?php echo formatTime($contract->begin, DT_DATE1)." to ". formatTime($contract->end, DT_DATE1);?></em></td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>
          <div class="detail">
            <div class="detail-title"><strong><?php echo $lang->contract->approval;?></strong></div>
            <div class="detail-content">
              <table class="pure-table pure-table-horizontal" style="width:100%;">
                  <?php $space = common::checkNotCN() ? ' ' : '';?>
                  <thead>
                  <tr>
                  <th><?php echo $lang->contract->sequence?></th>

                    <th><?php echo $lang->contract->user?></th>
                    <th><?php echo $lang->contract->sign?></th>
                  </tr>
                </thead>
                  <tbody>

                  <?php foreach($contractAP as $value){
                    echo "<tr>
                    <td>$value->order</td>

                    <td>$value->user</td>
                    <td>";
                    echo $value->sign==true?'YES':'No';
                    echo " </td>
                    
                    </tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php $this->printExtendFields($product, 'div', "position=right&inForm=0&inCell=1");?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-8 main-col">
    <div class="row">
    <div class="col-sm-<?php echo 12?>">
        <div class="panel block-dynamic">
          <div class="panel-heading">
          <div class="panel-title"><?php echo $lang->contract->invoiceList;?></div>
            <nav class="panel-actions nav nav-default">
              <li><a href="<?php echo $this->createLink('contract', 'invoiceList', "contractID={$contract->id}");?>" title="<?php echo $lang->more;?>"><i class="icon icon-more icon-sm"></i></i></a></li>
            </nav>
          </div>
          <div class="panel-body scrollbar-hover">
            <?php 
            if($invoice): ?>
                  <table class="pure-table pure-table-horizontal" style="width:100%;" >
                    <thead>
                      <tr>
                        <th><?php echo $lang->invoice->invoiceID?></th>
                        <th><?php echo $lang->invoice->refNo?></th>

                        <th><?php echo $lang->invoice->amount?></th>
                        <th><?php echo $lang->invoice->status?></th>
                      </tr>
                   </thead>
                   <tbody>
                     <?php foreach($invoice as $value){
                       echo "<tr><td><a href=". $this->createLink('contract', 'invoiceview', "invoiceID={$value->id}")." class='btn btn-link' title='$value->id'>$value->id</a></td>
                            <td><a href=". $this->createLink('contract', 'invoiceview', "invoiceID={$value->id}")." class='btn btn-link' title='$value->id'>$value->refNo</a></td>
                            <td>$value->amount</td>
                            <td>$value->status</td></tr>";
                     }?>
                   </tbody>
                </table>
            <?php endif;?>
            <?php if(!$invoice){
              echo "No Invoice";
            } ?>



          </div>
        </div>
      </div>




      <?php $this->printExtendFields($product, 'div', "position=left&inForm=0");?>
<!--

      <div class="col-sm-<?php echo 12?>">
        <div class="panel block-dynamic">
          <div class="panel-heading">
          <div class="panel-title"><?php echo $lang->product->latestDynamic;?></div>
            <nav class="panel-actions nav nav-default">
              <li><a href="<?php echo $this->createLink('product', 'dynamic', "productID={$product->id}&type=all");?>" title="<?php echo $lang->more;?>"><i class="icon icon-more icon-sm"></i></i></a></li>
            </nav>
          </div>
          <div class="panel-body scrollbar-hover">
            <ul class="timeline timeline-tag-left no-margin">
              <?php foreach($dynamics as $action):?>
              <li <?php if($action->major) echo "class='active'";?>>
                <div class='text-ellipsis'>
                  <span class="timeline-tag"><?php echo $action->date;?></span>
                  <span class="timeline-text"><?php echo zget($users, $action->actor) . ' ' . "<span class='label-action'>{$action->actionLabel}</span>" . $action->objectLabel . ' ' . html::a($action->objectLink, $action->objectName);?></span>
                </div>
              </li>
              <?php endforeach;?>
            </ul>
          </div>
        </div>
      </div>
              -->
      <div class="col-sm-12">
        <?php $blockHistory = true;?>
        <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=contract&objectID=$contract->id");?>
        <?php include '../../common/view/action.html.php';?>
      </div>
    </div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php
        $params = "contract=$contract->id";
        $browseLink =  inlink('browse', "productID=$contract->assetID");
        common::printBack($browseLink);
        if(!$contract->deleted)
        {
            //echo $this->buildOperateMenu($product, 'view');

            echo "<div class='divider'></div>";

           /* if($product->status != 'closed')
            {
                common::printIcon('product', 'close', $params, $product, 'button', '', '', 'iframe', true);
                echo "<div class='divider'></div>";
            }*/
            common::printIcon('contract', 'invoiceList', $params, $product);

            if(common::hasPriv("contract",'createInvoice')){
              common::printIcon('contract', 'createInvoice', $params, $product);
            }

            common::printIcon('contract', 'edit', $params, $product);
            common::printIcon('contract', 'delete', $params, $product, 'button', 'trash', 'hiddenwin');
        }
        ?>
      </div>
    </div>
  </div>
 
</div>
<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
</div>
<?php include '../../common/view/footer.html.php';?>
