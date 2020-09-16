

<!-- PAGE CONTENT BEGINS -->
<input type="hidden" value="{{ $page or 1 }}" id="page" /><!--当前页号-->
<input type="hidden" value="{{ $pagesize or 20 }}" id="pagesize"/><!--每页显示数量-->
<input type="hidden" value="{{ $total or -1 }}" id="total"/><!--总记录数量,小于0重新获取-->
