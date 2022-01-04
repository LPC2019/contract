<?php
/**
 * The sendmail file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @version     $Id: sendmail.html.php 3717 2020-11-03 15:35:07Z  tianshujie@easycorp.ltd $
 * @link        https://www.zentao.net
 */
?>
<p>
The <?php echo html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink('contract', 'invoiceView', "invoice=$invoice->id", 'html'), "Invoice #$invoice->id", '', "style='color: {$color}; text-decoration: underline;'");?> is ready for authorization, pleases check it in CDE </br>
</br>
<?php echo "<b>".html::a(zget($this->config->mail, 'domain', common::getSysURL()) . helper::createLink('contract', 'invoiceView', "invoice=$invoice->id", 'html'), "Click Here", '', "style='color: {$color}; text-decoration: underline;'")."</b>";?>

</p>

