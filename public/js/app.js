(function($){
	$.fn.keyHandle = function(e)
	{
		if(typeof e == 'undefined')return;
		var t = $(this);
		var obj = {
			
				code	: null,
				event	: e,
				selector: t
				
			};
		if(typeof e.which !== 'undefined')
		{
			obj.code = e.which;
		}
		else
		{
			obj.code = e.keyCode;
		}
		return obj;
	}
	
	$(document).on('keypress','.numeric,.currency',function(e){
		var j = $(this).keyHandle(e);
		var b = "8,9,13,35,36,37,39".match(new RegExp(j.code));
		if( !j.code || (49 <= j.code && j.code <= 57) || 48 == j.code || b )return;
		else e.preventDefault();
	});
	
	$(document).on('keypress','.decimal',function(e){
		var j = $(this).keyHandle(e);
		var b = "8,9,13,35,36,37,39".match(new RegExp(j.code));
		if( !j.code || (49 <= j.code && j.code <= 57) || 48 == j.code || 46 == j.code || b )return;
		else e.preventDefault();
	});
	
	$(document).on('change','.currency',function(e){
		$(this).trigger('keyup');
	});
	
	$(document).on('keyup','.currency',function(e){
		var t = $(this);
		var j = $(t).keyHandle(e);
		var b = "8,9,13,35,36,37,39".match(new RegExp(j.code));
		var s = t.data('sep') || ".";
		
		if( !j.code || (49 <= j.code && j.code <= 57) || 48 == j.code || !b )
		{
			var d = t.val().replace(/[^0-9]/gi,'');
			d = isNaN(parseInt(d)) ? 0 : parseInt(d);
			var n = d.toFixed(0).replace(/./g, function(c, i, a) {
				return i && c !== s && !((a.length - i) % 3) ? s + c : c;
			});
			t.val( n );	
		}
		else 
		{
			e.preventDefault();
		}
	});
	$(document).on('setAutoTable','.table-data-dynamic',function(e){

		var t = $(this);
		if(!t.hasClass('dataTable'))
		{
			var disableSortable = [$(this).find('th').length-1];
			var dd = t.data();
			var sorts = true;
			var unsortable = [];
			if( typeof dd.disableSort !== 'undefined' )
			{
				sorts = dd.disableSort;
				if( sorts )
				{
					var x = dd.disableSort.toString().replace(/[^0-9\,]/gi,'').split(',');
					disableSortable = [];
					if(x.length > 0)
					{
						x.forEach(function(i,d){
							var c = isNaN(parseInt(i)) ? 0 : parseInt(i);
							if($("th",t).eq(c).length > 0) {
								disableSortable.push(c);
								unsortable.push($("th",t).eq(c));
							}
						});
					}
					else
					{
						disableSortable.push(isNaN(parseInt(x)) ? 0 : parseInt(x));
						unsortable.push($("th",t).eq(parseInt(x)));
					}	
				}
			}
			var opt = {
					"bJQueryUI": false,
					"sPaginationType": "full_numbers",
					"bStateSave":(dd.stateSave || false)
			};
			if( dd.jActive )
			{
				opt.sAjaxSource = dd.tableUrl;
				opt.bProcessing = true;
				opt.bServerSide = dd.jActive;
				opt.sSearch = dd.search || '';
				opt.iDisplayStart = dd.start || 0;
				opt.iDisplayLength = dd.minLength || 10;
				opt.fnDrawCallback = function(o){
                                            $('.button',t).not('.ui-button').button();
                                    }
			}
			
			if(sorts){
				opt.aoColumnDefs = [{ "bSortable": false,"aTargets":disableSortable}];
			}

			t.dataTable(opt);
			var p = t.parent().parent().parent();
			if( t.parent().hasClass("dataTable") )
			    p = t.parent();
			    
			if(dd.disableLimit === true) {
				$(".dataTables_length",p).css({"visibility":"hidden"});
			}
			
			if(dd.disableFilter === true) {
				$(".dataTables_filter",p).css({"visibility":"hidden"});
			}
			
			if( (dd.disableLimit === true && dd.disableFilter === true) || dd.disableTools === true ){
				$(".dataTables_filter,.dataTables_length",p).hide();
			}
			
			if(dd.disablePaging === true) {
				$(".dataTables_paginate",p).css({"visibility":"hidden"});
			}
			
			if(dd.disableInfo === true) {
				$(".dataTables_info",p).css({"visibility":"hidden"});
			}
			
			if( (dd.disablePaging === true && dd.disableInfo === true) || dd.disableFooter === true ){
				$(".dataTables_info,.dataTables_paginate",p).hide();
			}
		}

	});
	
	$.fn.reset = function(){
		var _t = this;
		_t.trigger("beforeReset");
		if($("form").is(_t)){
			_t.trigger("reset").trigger("formReset");
		}
		return _t;
	};

	//form setup
	$.fn.simpleForm = function(opt){
		var _t = this;
		if(!$("form").is(_t))return;
		_t.trigger("beforeSet");
		opt = $.extend({
			url:(_t.attr("action")||window.location.href),
			cache:false,
			async:false,
			type:(_t.attr("method") || "POST")
		},opt);
		
		_t.data(opt);

		$.each(opt.data,function(a,b){
			var d = $('[name='+a+'],[name="'+a+'\[\]"]',_t);
			if($("[type=radio]").is(d)){
				$('[name='+a+'][value='+b+']',_t).trigger("click");
			}else if($("[type=checkbox]").is(d)){
				if($.isArray(b)){
					$.each(b,function(n,o){
						$('[name="'+a+'\[\]"][value='+o+'],[name='+a+'][value='+o+']',_t).trigger("click");
					});
				}else{
					$('[name="'+a+'\[\]"][value='+b+'],[name='+a+'][value='+b+']',_t).trigger("click");
				}
			}else if($("select[multiple]").is(d) && $.isArray(b)){
				$.each(b,function(n,o){
					var g = $('option[value='+o+']',d)[0];
					if(g)g.selected = true;
				});
			}else{
				if($.isArray(b)){
					$('[name="'+a+'\[\]"]',_t).each(function(r,s){
						$(s).val(b[r]);
					});
				}
				else{
					d.val(b);
				}
			}
		});
		_t.addClass("jquery-simple-form").trigger("Set");

		return _t;
	};

	//table setup
	$.fn.simpleFormAction = function(){
		var _t = $(this);
		var target = _t.data("formAction");
		$("[data-form-action]",_t).each(function(q,r){
			$(document).on("click",_t.selector+" [data-form-action]:eq("+q+")",function(e){
				var __t = $(this);
				var p = __t.parent();
				var row = p.parent();
				e.preventDefault();
				var D = {};
				$("th",_t).not(p).each(function(a,b){
					if($(b).data("field")){
						var y = $("td:eq("+a+")",row).text();
						if($(b).data("enum")){
							y = y.split(",");
						}
						D[$(b).data("field")] = y;
					}
				});
				if(__t.data("formAjax")){
					var _f = $(__t.data("formAction"));
					$("[type=submit]",_f).hide();
					$.ajax({
						url:(_t.data("formUrl") || _t.attr("href")),
						data:D,
						type:"GET",
						cache:false,
						async:false,
						dataType:"json",
						success:function(O){
							_f.reset().simpleForm({data:O});
							$("[type=submit]",_f).show();
						}
					});
				}else{
					$(__t.data("formAction")).reset().simpleForm({data:D});
				}
			});
		});
		
		$(document).on("submit",".jquery-simple-form",function(e){
			if($(this).data("ajax") === false)
				return true;
			e.preventDefault();
			var _t = $(this);
			var opt = _t.data();
			opt.data = _t.serialize();
			opt.success = function(data){
				if( opt.debug ){
					console.log(data);
				}
			};
			$.ajax(opt);
		});
	}

})(jQuery);

$(document).ready(function(){
	$(".table-data-dynamic").trigger("setAutoTable");
});