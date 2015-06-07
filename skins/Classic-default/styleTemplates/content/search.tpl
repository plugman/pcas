<!-- BEGIN: adv_search -->
<div class="boxContent">
  <form method="get" action="index.php" enctype="multipart/form-data">
  <div>
  <span class="txtContentTitle">{LANG_SEARCH_TITLE}</span>
  </div>
  <br />
  <div>
	{LANG_SEARCH_KEYWORD}<br />
	<input type="text" class="textbox" name="searchStr" />
  </div>
  <br />
  <div>
  	{LANG_SEARCH_PRICE}<br />
	<input type="text" class="textbox" style="width: 50px;" name="priceMin" /> - 
	<input type="text" class="textbox" style="width: 50px;" name="priceMax" />
  </div>
  <br />
  <div><input type="checkbox" name="inStock" value="true" /> {LANG_SEARCH_INSTOCK}</div>
  <br />
  <div>
  {LANG_SEARCH_CATEGORY}<br />
  <select name="category[]" class="textbox" multiple="multiple" size="10">
  <!-- BEGIN: adv_search_category -->
	<option value="{OPTION_VALUE}">{OPTION_TITLE}</option>
  <!-- END: adv_search_category -->
  </select><br />
  {LANG_SEARCH_CATEGORY_HELP}
  </div>
  <br />
  <div>
  <input type='hidden' name='_a' value='viewCat' />
  <input type="submit" class="submit" name="search" value="{LANG_SEARCH_SUBMIT}" /> <input type="reset" class="submit" value="{LANG_SEARCH_RESET}" /></div>
  </form>
</div>
<!-- END: adv_search -->