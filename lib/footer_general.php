<?php $checkLogin = GetLoginSerial(); ?>
    <dl>
        <dt><a href="/community.php">そり蔵公民館</a></dt>
        <?php GetContentsList("footer", 1); ?>
    </dl>
    <dl>
        <dt><a href="/support.php">製品サポート</a></dt>
        <?php GetContentsList("footer", 2); ?>
    </dl>
    <dl>
        <dt><a href="/program.php">プログラム更新</a></dt>
        <?php GetContentsList("footer", 3); ?>
    </dl>
    <dl>
        <dt><a href="/service.php">関連サービス</a></dt>
        <?php GetContentsList("footer", 4); ?>
    </dl>
</div>
<p>Copyright&copy;&nbsp;Sorimachi&nbsp;Co.,Ltd.&nbsp;All&nbsp;rights&nbsp;reserved.</p>

<form name="WDSend">
    <input type="hidden" name="SerialNo" value="<?= $checkLogin ?>">
</form>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-2097811-3', 'auto');
  ga('send', 'pageview');
</script>
