<?php 
if (!isset($backgroundcolor)) $backgroundcolor = "#333F5F";
if (!isset($hovercolor)) $hovercolor = "#000";

?>
<script type="text/javascript">
  var uservoiceJsHost = ("https:" == document.location.protocol) ? "https://uservoice.com" : "http://cdn.uservoice.com";
  document.write(unescape("%3Cscript src='" + uservoiceJsHost + "/javascripts/widgets/tab.js' type='text/javascript'%3E%3C/script%3E"))
</script>
<script type="text/javascript">
UserVoice.Tab.show({ 
  key: 'quickeval',
  host: 'quickeval.uservoice.com', 
  forum: 'general', 
  alignment: 'right',
  background_color:'<?php echo $backgroundcolor; ?>', 
  text_color: 'white',
  hover_color: '<?php echo $hovercolor; ?>',
  lang: 'en'
})
</script>