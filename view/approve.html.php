<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        https://www.zentao.pm
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>

    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $invoice->id;?></span>
        <?php //echo isonlybody() ? ("<span title='$task->name'>" . $task->name . '</span>') : html::a($this->createLink('task', 'view', 'task=' . $task->id), $task->name);?>

        <?php // if(!isonlybody()):?>
        <small> <?php echo html::a($this->createLink('product', 'view', 'asset=' . $asset->id), $asset->name).$lang->arrow.html::a($this->createLink('contract', 'view', 'asset=' . $contract->id), $contract->contractName).$lang->arrow .$lang->arrow.html::a($this->createLink('contract', 'Invoiceview', 'invoice=' . $invoice->id), $invoice->id).$lang->arrow . $lang->invoice->approve;?></small>
        <?php //endif;?>
      </h2>
    </div>
    <form method='post' target='hiddenwin'>
      <table class='table table-form'>

        

          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('description', '', "rows='6' class='form-control w-p98'");?></td>
        </tr>
        <tr><td></td><td ><span style="color:red;">*Pleases note that you can not change the approval after submit this form</span></td></tr>
        <tr>

          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::linkButton($lang->goback, $this->session->taskList);?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
